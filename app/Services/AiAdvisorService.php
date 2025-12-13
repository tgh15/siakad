<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Mahasiswa;
use App\Services\AkademikCalculationService;

class AiAdvisorService
{
    protected AkademikCalculationService $calculationService;
    protected string $apiKey;
    protected string $model = 'llama-3.3-70b-versatile'; // Groq Llama model

    public function __construct(AkademikCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
        $this->apiKey = config('services.groq.api_key', '');
    }

    /**
     * Send a chat message to Groq with student context
     */
    public function chat(Mahasiswa $mahasiswa, string $message, array $history = []): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'API key belum dikonfigurasi. Silakan hubungi administrator.',
            ];
        }

        $context = $this->buildContext($mahasiswa);
        $systemPrompt = $this->buildSystemPrompt($context);

        // Build messages for Groq (OpenAI-compatible format)
        $messages = [];
        
        // Add system prompt
        $messages[] = [
            'role' => 'system',
            'content' => $systemPrompt
        ];

        // Add conversation history
        foreach ($history as $msg) {
            $messages[] = [
                'role' => $msg['role'] === 'user' ? 'user' : 'assistant',
                'content' => $msg['content']
            ];
        }

        // Add current message
        $messages[] = [
            'role' => 'user',
            'content' => $message
        ];

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $this->model,
                    'messages' => $messages,
                    'temperature' => 0.7,
                    'max_completion_tokens' => 1024,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['choices'][0]['message']['content'] ?? 'Maaf, saya tidak bisa memberikan respons saat ini.';
                
                return [
                    'success' => true,
                    'message' => $text,
                ];
            }

            $error = $response->json();
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan respons dari AI: ' . ($error['error']['message'] ?? 'Unknown error'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Build context from mahasiswa data
     */
    protected function buildContext(Mahasiswa $mahasiswa): array
    {
        $mahasiswa->load(['user', 'prodi.fakultas', 'dosenPa.user']);

        // Get IPK data
        $ipkData = $this->calculationService->calculateIPK($mahasiswa);
        
        // Get IPS history
        $ipsHistory = $this->calculationService->getIPSHistory($mahasiswa);
        
        // Get grade distribution
        $gradeDistribution = $this->calculationService->getGradeDistribution($mahasiswa);
        
        // Get transcript with details
        $transcript = $this->calculationService->getTranscript($mahasiswa);

        // Get jadwal kuliah semester aktif
        $jadwalList = \App\Models\JadwalKuliah::whereHas('kelas.krsDetail.krs', function($q) use ($mahasiswa) {
            $q->where('mahasiswa_id', $mahasiswa->id)
              ->where('status', 'approved')
              ->whereHas('tahunAkademik', fn($ta) => $ta->where('is_active', true));
        })->with('kelas.mataKuliah')->get();

        $jadwal = $jadwalList->map(fn($j) => [
            'hari' => $j->hari,
            'jam' => substr($j->jam_mulai, 0, 5) . '-' . substr($j->jam_selesai, 0, 5),
            'matkul' => $j->kelas->mataKuliah->nama_mk ?? '-',
            'ruangan' => $j->ruangan ?? '-',
        ])->toArray();

        // Get presensi rekap semester aktif
        $presensiService = app(\App\Services\PresensiService::class);
        $kelasList = \App\Models\Kelas::whereHas('krsDetail.krs', function($q) use ($mahasiswa) {
            $q->where('mahasiswa_id', $mahasiswa->id)
              ->where('status', 'approved')
              ->whereHas('tahunAkademik', fn($ta) => $ta->where('is_active', true));
        })->with('mataKuliah')->get();

        $presensi = $kelasList->map(function($kelas) use ($mahasiswa, $presensiService) {
            $rekap = $presensiService->getRekapPresensi($mahasiswa->id, $kelas->id);
            return [
                'matkul' => $kelas->mataKuliah->nama_mk ?? '-',
                'hadir' => $rekap['hadir'],
                'sakit' => $rekap['sakit'],
                'izin' => $rekap['izin'],
                'alpa' => $rekap['alpa'],
                'persentase' => $rekap['persentase'] . '%',
            ];
        })->toArray();

        // Get detailed grades per course
        $detailNilai = [];
        foreach ($transcript['semesters'] ?? [] as $sem) {
            foreach ($sem['courses'] ?? [] as $course) {
                $detailNilai[] = [
                    'semester' => $sem['semester'],
                    'kode' => $course['kode'],
                    'matkul' => $course['nama'],
                    'sks' => $course['sks'],
                    'nilai' => $course['nilai_huruf'],
                ];
            }
        }

        return [
            'nama' => $mahasiswa->user->name,
            'nim' => $mahasiswa->nim,
            'prodi' => $mahasiswa->prodi->nama ?? '-',
            'fakultas' => $mahasiswa->prodi->fakultas->nama ?? '-',
            'angkatan' => $mahasiswa->angkatan,
            'dosen_pa' => $mahasiswa->dosenPa->user->name ?? '-',
            'ipk' => $ipkData['ips'],
            'total_sks' => $ipkData['total_sks'],
            'ips_history' => $ipsHistory->map(fn($s) => [
                'semester' => $s['tahun_akademik'],
                'ips' => $s['ips'],
                'sks' => $s['total_sks'],
            ])->values()->toArray(),
            'grade_distribution' => $gradeDistribution,
            'max_sks' => $this->calculationService->getMaxSKS($ipsHistory->filter(fn($s) => $s['ips'] > 0)->last()['ips'] ?? 0),
            'jadwal' => $jadwal,
            'presensi' => $presensi,
            'detail_nilai' => $detailNilai,
        ];
    }

    /**
     * Build system prompt with context
     */
    protected function buildSystemPrompt(array $context): string
    {
        $ipsHistoryStr = collect($context['ips_history'])
            ->map(fn($s) => "Semester {$s['semester']}: IPS={$s['ips']}, SKS={$s['sks']}")
            ->join(" | ") ?: "Tidak ada data";

        $gradeStr = collect($context['grade_distribution'])
            ->map(fn($count, $grade) => "$grade:$count")
            ->join(', ') ?: "Tidak ada data";

        $jadwalStr = collect($context['jadwal'])
            ->map(fn($j) => "{$j['hari']} {$j['jam']} - {$j['matkul']} (R.{$j['ruangan']})")
            ->join(" | ") ?: "Tidak ada jadwal semester ini";

        $presensiStr = collect($context['presensi'])
            ->map(fn($p) => "{$p['matkul']}: Hadir={$p['hadir']}, Sakit={$p['sakit']}, Izin={$p['izin']}, Alpa={$p['alpa']}, Persentase={$p['persentase']}")
            ->join(" | ") ?: "Tidak ada data presensi";

        $nilaiDetailStr = collect($context['detail_nilai'])
            ->map(fn($c) => "[{$c['semester']}] {$c['kode']} {$c['matkul']} ({$c['sks']}SKS) = {$c['nilai']}")
            ->join(" | ") ?: "Tidak ada data nilai";

        // Calculate additional analytics
        $totalMK = count($context['detail_nilai']);
        $nilaiA = collect($context['detail_nilai'])->filter(fn($n) => in_array($n['nilai'], ['A', 'A-']))->count();
        $nilaiB = collect($context['detail_nilai'])->filter(fn($n) => str_starts_with($n['nilai'] ?? '', 'B'))->count();
        $nilaiC = collect($context['detail_nilai'])->filter(fn($n) => str_starts_with($n['nilai'] ?? '', 'C'))->count();
        $nilaiD = collect($context['detail_nilai'])->filter(fn($n) => str_starts_with($n['nilai'] ?? '', 'D'))->count();
        $nilaiE = collect($context['detail_nilai'])->filter(fn($n) => ($n['nilai'] ?? '') === 'E')->count();
        
        $avgPresensi = collect($context['presensi'])->avg(fn($p) => (float) str_replace('%', '', $p['persentase'])) ?? 0;
        $lowPresensi = collect($context['presensi'])->filter(fn($p) => (float) str_replace('%', '', $p['persentase']) < 80)->pluck('matkul')->join(', ');

        $ipsTrend = 'stabil';
        $ipsValues = collect($context['ips_history'])->pluck('ips')->filter(fn($v) => $v > 0)->values();
        if ($ipsValues->count() >= 2) {
            $last = $ipsValues->last();
            $prev = $ipsValues->slice(-2, 1)->first();
            if ($last > $prev + 0.1) $ipsTrend = 'meningkat';
            elseif ($last < $prev - 0.1) $ipsTrend = 'menurun';
        }

        return <<<PROMPT
<SYSTEM_IDENTITY>
Kamu adalah AI Academic Advisor untuk SIAKAD Universitas Riau. Kamu adalah asisten akademik yang sangat cerdas, analitis, dan profesional.
</SYSTEM_IDENTITY>

<GROUNDING_RULES>
ATURAN KRITIS - WAJIB DIPATUHI:
1. HANYA gunakan data yang tersedia di bawah. JANGAN PERNAH mengarang/menebak data yang tidak ada.
2. Jika ditanya sesuatu yang datanya tidak tersedia, JAWAB dengan jujur: "Maaf, data tersebut tidak tersedia dalam sistem."
3. Jika diminta menghitung sesuatu, gunakan ANGKA PERSIS dari data, bukan perkiraan.
4. JANGAN PERNAH mengarang nama dosen, mata kuliah, atau nilai yang tidak ada dalam data.
5. Setiap klaim harus bisa diverifikasi dari data yang diberikan.
</GROUNDING_RULES>

<STUDENT_DATABASE>
IDENTITAS:
- Nama Lengkap: {$context['nama']}
- NIM: {$context['nim']}
- Program Studi: {$context['prodi']}
- Fakultas: {$context['fakultas']}
- Tahun Angkatan: {$context['angkatan']}
- Dosen Pembimbing Akademik: {$context['dosen_pa']}

STATISTIK AKADEMIK:
- IPK Kumulatif: {$context['ipk']}
- Total SKS Lulus: {$context['total_sks']} SKS
- Maksimum SKS Semester Depan: {$context['max_sks']} SKS
- Jumlah Mata Kuliah Selesai: {$totalMK}
- Trend IPS: {$ipsTrend}

DISTRIBUSI NILAI:
- Nilai A/A-: {$nilaiA} mata kuliah
- Nilai B+/B/B-: {$nilaiB} mata kuliah
- Nilai C+/C: {$nilaiC} mata kuliah
- Nilai D: {$nilaiD} mata kuliah
- Nilai E: {$nilaiE} mata kuliah

RIWAYAT IPS:
{$ipsHistoryStr}

JADWAL KULIAH AKTIF:
{$jadwalStr}

REKAP PRESENSI (Rata-rata: {$avgPresensi}%):
{$presensiStr}
Mata kuliah presensi rendah (<80%): {$lowPresensi}

DETAIL SEMUA NILAI:
{$nilaiDetailStr}
</STUDENT_DATABASE>

<ANALYSIS_FRAMEWORK>
Saat menjawab, gunakan pendekatan analisis mendalam:
1. IDENTIFIKASI: Pahami pertanyaan dengan tepat
2. EKSTRAK: Ambil data relevan dari database mahasiswa
3. ANALISIS: Lakukan perhitungan/perbandingan jika diperlukan
4. SINTESIS: Gabungkan temuan menjadi insight bermakna
5. REKOMENDASI: Berikan saran actionable berdasarkan analisis
</ANALYSIS_FRAMEWORK>

<OUTPUT_FORMAT>
ATURAN FORMAT RESPONS:
1. Gunakan bahasa Indonesia profesional dan natural
2. JANGAN gunakan heading markdown (# atau ##)
3. JANGAN gunakan emoji
4. Gunakan **bold** untuk angka/data penting
5. Gunakan paragraf yang mengalir, bukan list panjang
6. Jika perlu list, gunakan bullet (-) dengan singkat
7. Respons harus informatif namun ringkas
8. Sertakan data numerik spesifik untuk mendukung setiap klaim
</OUTPUT_FORMAT>

<CAPABILITY_EXAMPLES>
Contoh kemampuan analisis:
- "Berdasarkan data, IPS Anda menunjukkan trend {$ipsTrend}..."
- "Dari {$totalMK} mata kuliah, Anda memiliki {$nilaiA} nilai A/A-..."
- "Rata-rata presensi Anda adalah {$avgPresensi}%..."
- "Dengan IPS terakhir, Anda dapat mengambil maksimal {$context['max_sks']} SKS..."
</CAPABILITY_EXAMPLES>

Siap menerima pertanyaan mahasiswa.
PROMPT;
    }
}

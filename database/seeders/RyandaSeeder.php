<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Kelas;
use App\Models\TahunAkademik;
use App\Models\Krs;
use App\Models\KrsDetail;
use App\Models\Nilai;
use App\Models\JadwalKuliah;
use App\Models\Ruangan;

class RyandaSeeder extends Seeder
{
    /**
     * Data Riil - Universitas Riau
     * JANGAN DIUBAH - Sesuai data asli mahasiswa
     */
    public function run(): void
    {
        // ========================================
        // 1. TAHUN AKADEMIK
        // ========================================
        $ta2023Ganjil = TahunAkademik::create(['tahun' => '2023', 'semester' => 'ganjil', 'is_active' => false]);
        $ta2024Genap = TahunAkademik::create(['tahun' => '2024', 'semester' => 'genap', 'is_active' => false]);
        $ta2024Ganjil = TahunAkademik::create(['tahun' => '2024', 'semester' => 'ganjil', 'is_active' => false]);
        $ta2025Genap = TahunAkademik::create(['tahun' => '2025', 'semester' => 'genap', 'is_active' => false]);
        $ta2025Ganjil = TahunAkademik::create(['tahun' => '2025', 'semester' => 'ganjil', 'is_active' => true]); // Semester 5 aktif

        // ========================================
        // 2. FAKULTAS - Universitas Riau
        // ========================================
        $fmipa = Fakultas::create(['nama' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam']);
        $feb = Fakultas::create(['nama' => 'Fakultas Ekonomi dan Bisnis']);

        // ========================================
        // 3. PROGRAM STUDI
        // ========================================
        $prodiSI = Prodi::create([
            'fakultas_id' => $fmipa->id,
            'nama' => 'Sistem Informasi',
        ]);

        $prodiEP = Prodi::create([
            'fakultas_id' => $feb->id,
            'nama' => 'Ekonomi Pembangunan',
        ]);

        // ========================================
        // 4. RUANGAN
        // ========================================
        $ruanganB21 = Ruangan::create(['kode_ruangan' => 'B-21', 'nama_ruangan' => 'Ruang B-21', 'kapasitas' => 40, 'gedung' => 'Gedung B']);
        $ruanganA21 = Ruangan::create(['kode_ruangan' => 'A-21', 'nama_ruangan' => 'Ruang A-21', 'kapasitas' => 40, 'gedung' => 'Gedung A']);

        // ========================================
        // 5. DOSEN - Gita Sastria, M.IT (Dosen PA)
        // ========================================
        $userDosen = User::create([
            'name' => 'Gita Sastria, M.IT',
            'email' => 'gita.sastria@unri.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        $dosenGita = Dosen::create([
            'user_id' => $userDosen->id,
            'nidn' => '0015078901',
            'prodi_id' => $prodiSI->id,
        ]);

        // ========================================
        // 6. MAHASISWA - Ryanda Valents Anakri
        // ========================================
        $userRyanda = User::create([
            'name' => 'Ryanda Valents Anakri',
            'email' => 'ryanda.valents3649@student.unri.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        $ryanda = Mahasiswa::create([
            'user_id' => $userRyanda->id,
            'nim' => '2303113649',
            'prodi_id' => $prodiSI->id,
            'dosen_pa_id' => $dosenGita->id,
            'angkatan' => 2023,
            'status' => 'aktif',
        ]);

        // ========================================
        // 7. MATA KULIAH SISTEM INFORMASI
        // ========================================
        
        // --- SEMESTER 1 (22 SKS) ---
        $mkSem1 = [
            ['kode_mk' => 'MSI1101', 'nama_mk' => 'Arsitektur dan Organisasi Komputer', 'sks' => 3, 'semester' => 1],
            ['kode_mk' => 'UXN1009', 'nama_mk' => 'Bahasa Indonesia', 'sks' => 2, 'semester' => 1],
            ['kode_mk' => 'UNR1002', 'nama_mk' => 'Bahasa Inggris', 'sks' => 1, 'semester' => 1],
            ['kode_mk' => 'MSI1102', 'nama_mk' => 'Konsep Pemrograman', 'sks' => 4, 'semester' => 1],
            ['kode_mk' => 'MSI1103', 'nama_mk' => 'Manajemen dan Organisasi', 'sks' => 2, 'semester' => 1],
            ['kode_mk' => 'UXN1001', 'nama_mk' => 'Pendidikan Agama Islam', 'sks' => 2, 'semester' => 1],
            ['kode_mk' => 'UXN1008', 'nama_mk' => 'Pendidikan Kewarganegaraan', 'sks' => 2, 'semester' => 1],
            ['kode_mk' => 'MSI1105', 'nama_mk' => 'Statistika dan Probabilitas', 'sks' => 3, 'semester' => 1],
            ['kode_mk' => 'MSI1104', 'nama_mk' => 'Teknologi Multimedia', 'sks' => 3, 'semester' => 1],
        ];

        // --- SEMESTER 2 (21 SKS) ---
        $mkSem2 = [
            ['kode_mk' => 'UNR1003', 'nama_mk' => 'Budaya Melayu', 'sks' => 2, 'semester' => 2],
            ['kode_mk' => 'UNR1004', 'nama_mk' => 'Ilmu Lingkungan dan Mitigasi Bencana', 'sks' => 2, 'semester' => 2],
            ['kode_mk' => 'UNR1005', 'nama_mk' => 'Kewirausahaan', 'sks' => 2, 'semester' => 2],
            ['kode_mk' => 'MSI1201', 'nama_mk' => 'Konsep Basis Data', 'sks' => 3, 'semester' => 2],
            ['kode_mk' => 'UNR1001', 'nama_mk' => 'Literasi Digital', 'sks' => 1, 'semester' => 2],
            ['kode_mk' => 'MSI1202', 'nama_mk' => 'Matematika Diskrit', 'sks' => 3, 'semester' => 2],
            ['kode_mk' => 'MSI1203', 'nama_mk' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'semester' => 2],
            ['kode_mk' => 'UXN1007', 'nama_mk' => 'Pendidikan Pancasila', 'sks' => 2, 'semester' => 2],
            ['kode_mk' => 'MSI1204', 'nama_mk' => 'Sistem Operasi', 'sks' => 3, 'semester' => 2],
        ];

        // --- SEMESTER 3 (21 SKS) ---
        $mkSem3 = [
            ['kode_mk' => 'MSI2101', 'nama_mk' => 'Algoritma dan Struktur Data', 'sks' => 3, 'semester' => 3],
            ['kode_mk' => 'MSI2102', 'nama_mk' => 'Aljabar Linier dan Vektor', 'sks' => 3, 'semester' => 3],
            ['kode_mk' => 'MSI2103', 'nama_mk' => 'Basis Data Lanjut', 'sks' => 3, 'semester' => 3],
            ['kode_mk' => 'MSI2104', 'nama_mk' => 'Jaringan Komputer', 'sks' => 3, 'semester' => 3],
            ['kode_mk' => 'MSI2105', 'nama_mk' => 'Pengembangan Antarmuka Pengguna Sistem Informasi', 'sks' => 3, 'semester' => 3],
            ['kode_mk' => 'MSI2106', 'nama_mk' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'semester' => 3],
            ['kode_mk' => 'MSI2107', 'nama_mk' => 'Sistem Informasi Manajemen', 'sks' => 3, 'semester' => 3],
        ];

        // --- SEMESTER 4 (23 SKS) ---
        $mkSem4 = [
            ['kode_mk' => 'MSI3201', 'nama_mk' => 'Etika Profesi', 'sks' => 2, 'semester' => 4],
            ['kode_mk' => 'MSI2201', 'nama_mk' => 'Keamanan Sistem Informasi', 'sks' => 3, 'semester' => 4],
            ['kode_mk' => 'MSI2202', 'nama_mk' => 'Komputasi Awan', 'sks' => 3, 'semester' => 4],
            ['kode_mk' => 'MSI2207', 'nama_mk' => 'Pemrograman Bahasa Alami', 'sks' => 3, 'semester' => 4],
            ['kode_mk' => 'MSI2203', 'nama_mk' => 'Pengembangan Sistem Informasi Berbasis Web', 'sks' => 3, 'semester' => 4],
            ['kode_mk' => 'MSI2205', 'nama_mk' => 'Rekayasa Proses Bisnis', 'sks' => 3, 'semester' => 4],
            ['kode_mk' => 'MSI2204', 'nama_mk' => 'Sistem Cerdas', 'sks' => 3, 'semester' => 4],
            ['kode_mk' => 'MSI2206', 'nama_mk' => 'Sistem Informasi Geografis', 'sks' => 3, 'semester' => 4],
        ];

        // --- SEMESTER 5 (18 SKS) - KRS AKTIF ---
        $mkSem5 = [
            ['kode_mk' => 'MSI3105', 'nama_mk' => 'Pengembangan Aplikasi Perangkat Bergerak', 'sks' => 3, 'semester' => 5],
            ['kode_mk' => 'MSI3108', 'nama_mk' => 'PSI Berbasis Web Lanjut', 'sks' => 3, 'semester' => 5],
            ['kode_mk' => 'MSI3103', 'nama_mk' => 'Metodologi Penelitian', 'sks' => 3, 'semester' => 5],
            ['kode_mk' => 'MSI3104', 'nama_mk' => 'Perencanaan Sumber Daya Perusahaan', 'sks' => 3, 'semester' => 5],
            ['kode_mk' => 'MSI3106', 'nama_mk' => 'Tata Kelola Sistem Informasi', 'sks' => 3, 'semester' => 5],
            ['kode_mk' => 'MSI3101', 'nama_mk' => 'Data Mining', 'sks' => 3, 'semester' => 5],
        ];

        // --- SEMESTER 6-8 (Kurikulum Lanjutan) ---
        $mkSem6 = [
            ['kode_mk' => 'MSI3202', 'nama_mk' => 'Komunikasi Antar Pribadi', 'sks' => 2, 'semester' => 6],
            ['kode_mk' => 'MSI3203', 'nama_mk' => 'Manajemen Proyek SI', 'sks' => 3, 'semester' => 6],
            ['kode_mk' => 'MSI3204', 'nama_mk' => 'Perancangan Strategis SI', 'sks' => 3, 'semester' => 6],
            ['kode_mk' => 'MSI3205', 'nama_mk' => 'Sistem Temu Kembali', 'sks' => 3, 'semester' => 6],
            ['kode_mk' => 'MSI3206', 'nama_mk' => 'BI dan Data Warehouse', 'sks' => 3, 'semester' => 6],
            ['kode_mk' => 'MSI3207', 'nama_mk' => 'Manajemen Risiko SI', 'sks' => 3, 'semester' => 6],
            ['kode_mk' => 'MSI3208', 'nama_mk' => 'Kerja Praktek', 'sks' => 3, 'semester' => 6],
        ];

        $mkSem7 = [
            ['kode_mk' => 'UNR2001', 'nama_mk' => 'Kuliah Kerja Nyata (KKN)', 'sks' => 4, 'semester' => 7],
            ['kode_mk' => 'MSI4101', 'nama_mk' => 'Audit Sistem Informasi', 'sks' => 3, 'semester' => 7],
            ['kode_mk' => 'MSI4102', 'nama_mk' => 'Big Data', 'sks' => 3, 'semester' => 7],
            ['kode_mk' => 'MSI4103', 'nama_mk' => 'Kapita Selekta', 'sks' => 3, 'semester' => 7],
            ['kode_mk' => 'MSI4104', 'nama_mk' => 'Proyek Pengembangan SI', 'sks' => 3, 'semester' => 7],
        ];

        $mkSem8 = [
            ['kode_mk' => 'MSI4201', 'nama_mk' => 'Skripsi', 'sks' => 6, 'semester' => 8],
        ];

        // Create all mata kuliah
        $allMatkul = array_merge($mkSem1, $mkSem2, $mkSem3, $mkSem4, $mkSem5, $mkSem6, $mkSem7, $mkSem8);
        $matkulModels = [];
        foreach ($allMatkul as $mk) {
            $matkulModels[$mk['kode_mk']] = MataKuliah::create($mk);
        }

        // ========================================
        // 8. KELAS & NILAI HISTORIS (SEMESTER 1-4)
        // ========================================
        
        $nilaiHistoris = [
            // Semester 1
            ['kode' => 'MSI1101', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2023Ganjil],
            ['kode' => 'UXN1009', 'nilai_huruf' => 'A-', 'nilai_angka' => 80, 'ta' => $ta2023Ganjil],
            ['kode' => 'UNR1002', 'nilai_huruf' => 'A-', 'nilai_angka' => 80, 'ta' => $ta2023Ganjil],
            ['kode' => 'MSI1102', 'nilai_huruf' => 'A-', 'nilai_angka' => 80, 'ta' => $ta2023Ganjil],
            ['kode' => 'MSI1103', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2023Ganjil],
            ['kode' => 'UXN1001', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2023Ganjil],
            ['kode' => 'UXN1008', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2023Ganjil],
            ['kode' => 'MSI1105', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2023Ganjil],
            ['kode' => 'MSI1104', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2023Ganjil],
            
            // Semester 2
            ['kode' => 'UNR1003', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2024Genap],
            ['kode' => 'UNR1004', 'nilai_huruf' => 'A-', 'nilai_angka' => 80, 'ta' => $ta2024Genap],
            ['kode' => 'UNR1005', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2024Genap],
            ['kode' => 'MSI1201', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2024Genap],
            ['kode' => 'UNR1001', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2024Genap],
            ['kode' => 'MSI1202', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2024Genap],
            ['kode' => 'MSI1203', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2024Genap],
            ['kode' => 'UXN1007', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2024Genap],
            ['kode' => 'MSI1204', 'nilai_huruf' => 'A-', 'nilai_angka' => 80, 'ta' => $ta2024Genap],
            
            // Semester 3
            ['kode' => 'MSI2101', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2024Ganjil],
            ['kode' => 'MSI2102', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2024Ganjil],
            ['kode' => 'MSI2103', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2024Ganjil],
            ['kode' => 'MSI2104', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2024Ganjil],
            ['kode' => 'MSI2105', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2024Ganjil],
            ['kode' => 'MSI2106', 'nilai_huruf' => 'A-', 'nilai_angka' => 80, 'ta' => $ta2024Ganjil],
            ['kode' => 'MSI2107', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2024Ganjil],
            
            // Semester 4
            ['kode' => 'MSI3201', 'nilai_huruf' => 'A-', 'nilai_angka' => 80, 'ta' => $ta2025Genap],
            ['kode' => 'MSI2201', 'nilai_huruf' => 'A-', 'nilai_angka' => 80, 'ta' => $ta2025Genap],
            ['kode' => 'MSI2202', 'nilai_huruf' => 'B+', 'nilai_angka' => 75, 'ta' => $ta2025Genap],
            ['kode' => 'MSI2207', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2025Genap],
            ['kode' => 'MSI2203', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2025Genap],
            ['kode' => 'MSI2205', 'nilai_huruf' => 'A-', 'nilai_angka' => 80, 'ta' => $ta2025Genap],
            ['kode' => 'MSI2204', 'nilai_huruf' => 'A-', 'nilai_angka' => 80, 'ta' => $ta2025Genap],
            ['kode' => 'MSI2206', 'nilai_huruf' => 'A', 'nilai_angka' => 85, 'ta' => $ta2025Genap],
        ];

        // Create KRS historis for semester 1-4 (approved) and nilai
        $semesterTahunAkademik = [
            1 => $ta2023Ganjil,
            2 => $ta2024Genap,
            3 => $ta2024Ganjil,
            4 => $ta2025Genap,
        ];

        foreach ([1, 2, 3, 4] as $sem) {
            $krs = Krs::create([
                'mahasiswa_id' => $ryanda->id,
                'tahun_akademik_id' => $semesterTahunAkademik[$sem]->id,
                'status' => 'approved',
            ]);

            // Get matkul for this semester and create kelas + nilai
            foreach ($nilaiHistoris as $nh) {
                $mk = $matkulModels[$nh['kode']] ?? null;
                if ($mk && $mk->semester == $sem) {
                    // Create kelas
                    $kelas = Kelas::create([
                        'mata_kuliah_id' => $mk->id,
                        'dosen_id' => $dosenGita->id,
                        'nama_kelas' => 'SI-' . $sem . '-A',
                        'kapasitas' => 40,
                        'is_closed' => true,
                    ]);

                    // Create KRS Detail
                    KrsDetail::create([
                        'krs_id' => $krs->id,
                        'kelas_id' => $kelas->id,
                    ]);

                    // Create Nilai
                    Nilai::create([
                        'mahasiswa_id' => $ryanda->id,
                        'kelas_id' => $kelas->id,
                        'nilai_angka' => $nh['nilai_angka'],
                        'nilai_huruf' => $nh['nilai_huruf'],
                    ]);
                }
            }
        }

        // ========================================
        // 9. KRS AKTIF SEMESTER 5 + JADWAL
        // ========================================
        
        $krsSem5 = Krs::create([
            'mahasiswa_id' => $ryanda->id,
            'tahun_akademik_id' => $ta2025Ganjil->id,
            'status' => 'approved',
        ]);

        $jadwalSem5 = [
            ['kode' => 'MSI3105', 'hari' => 'Senin', 'jam_mulai' => '10:10', 'jam_selesai' => '12:40', 'ruangan' => 'B-21'],
            ['kode' => 'MSI3108', 'hari' => 'Senin', 'jam_mulai' => '13:00', 'jam_selesai' => '15:30', 'ruangan' => 'A-21'],
            ['kode' => 'MSI3103', 'hari' => 'Selasa', 'jam_mulai' => '10:10', 'jam_selesai' => '12:40', 'ruangan' => 'B-21'],
            ['kode' => 'MSI3104', 'hari' => 'Selasa', 'jam_mulai' => '13:00', 'jam_selesai' => '15:30', 'ruangan' => 'B-21'],
            ['kode' => 'MSI3106', 'hari' => 'Rabu', 'jam_mulai' => '10:10', 'jam_selesai' => '12:40', 'ruangan' => 'B-21'],
            ['kode' => 'MSI3101', 'hari' => 'Rabu', 'jam_mulai' => '13:00', 'jam_selesai' => '15:30', 'ruangan' => 'B-21'],
        ];

        foreach ($jadwalSem5 as $j) {
            $mk = $matkulModels[$j['kode']];
            
            // Create kelas
            $kelas = Kelas::create([
                'mata_kuliah_id' => $mk->id,
                'dosen_id' => $dosenGita->id,
                'nama_kelas' => 'SI-5-B21',
                'kapasitas' => 40,
                'is_closed' => false,
            ]);

            // Create KRS Detail
            KrsDetail::create([
                'krs_id' => $krsSem5->id,
                'kelas_id' => $kelas->id,
            ]);

            // Create Jadwal
            JadwalKuliah::create([
                'kelas_id' => $kelas->id,
                'hari' => $j['hari'],
                'jam_mulai' => $j['jam_mulai'],
                'jam_selesai' => $j['jam_selesai'],
                'ruangan' => $j['ruangan'],
            ]);
        }

        // ========================================
        // 10. KELAS UNTUK SEMESTER 6, 7, 8 (Kurikulum Lanjutan)
        // ========================================
        
        // Semester 6 Classes
        foreach ($mkSem6 as $mkData) {
            $mk = $matkulModels[$mkData['kode_mk']];
            Kelas::create([
                'mata_kuliah_id' => $mk->id,
                'dosen_id' => $dosenGita->id,
                'nama_kelas' => 'SI-6-A',
                'kapasitas' => 40,
                'is_closed' => false,
            ]);
        }

        // Semester 7 Classes
        foreach ($mkSem7 as $mkData) {
            $mk = $matkulModels[$mkData['kode_mk']];
            Kelas::create([
                'mata_kuliah_id' => $mk->id,
                'dosen_id' => $dosenGita->id,
                'nama_kelas' => 'SI-7-A',
                'kapasitas' => 40,
                'is_closed' => false,
            ]);
        }

        // Semester 8 Classes (Skripsi)
        foreach ($mkSem8 as $mkData) {
            $mk = $matkulModels[$mkData['kode_mk']];
            Kelas::create([
                'mata_kuliah_id' => $mk->id,
                'dosen_id' => $dosenGita->id,
                'nama_kelas' => 'SI-8-A',
                'kapasitas' => 40,
                'is_closed' => false,
            ]);
        }

        // ========================================
        // 11. ADMIN USER
        // ========================================
        User::create([
            'name' => 'Admin SIAKAD UNRI',
            'email' => 'admin@unri.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->command->info('✅ RyandaSeeder completed!');
        $this->command->info('   - 1 Mahasiswa: Ryanda Valents Anakri (2303113649)');
        $this->command->info('   - 1 Dosen PA: Gita Sastria, M.IT');
        $this->command->info('   - 2 Fakultas: FMIPA, FEB');
        $this->command->info('   - 2 Prodi: Sistem Informasi, Ekonomi Pembangunan');
        $this->command->info('   - 45 Mata Kuliah SI');
        $this->command->info('   - KHS Semester 1-4 dengan nilai');
        $this->command->info('   - KRS Semester 5 dengan jadwal');
    }
}

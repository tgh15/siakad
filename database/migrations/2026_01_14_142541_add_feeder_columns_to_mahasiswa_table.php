<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            // ID Identitas dari Feeder (UUID)
            $table->uuid('id_reg_pd')->nullable()->unique()->after('angkatan')->comment('Key Utama Registrasi Mahasiswa di Feeder');
            $table->uuid('id_pd')->nullable()->index()->comment('ID Mahasiswa di Feeder');
            $table->uuid('id_sp')->nullable()->comment('ID Satuan Pendidikan / Kampus');
            $table->uuid('id_sms')->nullable()->comment('ID Program Studi di Feeder');
            
            // Data Masuk & Keluar
            $table->date('tgl_masuk_sp')->nullable();
            $table->date('tgl_keluar')->nullable();
            $table->string('mulai_smt', 5)->nullable()->comment('Contoh: 20181');
            $table->string('smt_yudisium', 5)->nullable();
            
            // Referensi Kode (Disimpan integer/string sesuai referensi PDDIKTI)
            $table->integer('id_jns_daftar')->nullable();
            $table->integer('id_jns_keluar')->nullable();
            $table->integer('id_jalur_masuk')->nullable();
            $table->integer('id_pembiayaan')->nullable();
            
            // Akademik & Kelulusan
            $table->decimal('ipk', 4, 2)->nullable()->default(0);
            $table->string('no_seri_ijazah')->nullable();
            $table->string('sk_yudisium')->nullable();
            $table->date('tgl_sk_yudisium')->nullable();
            $table->date('tgl_terbit_ijazah')->nullable();
            $table->string('judul_skripsi')->nullable(); // Bisa ganti ->text() jika judul panjang
            $table->decimal('sks_diakui', 5, 2)->nullable();
            
            // Metadata & Sync Info
            $table->dateTime('last_update')->nullable();
            $table->dateTime('last_sync')->nullable();
            $table->string('soft_delete', 1)->default('0');
            $table->string('keterangan')->nullable(); // Mapping dari 'ket'
            
            // Kolom asal data (Optional)
            $table->string('asal_data_ijazah', 1)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn([
                'id_reg_pd', 'id_pd', 'id_sp', 'id_sms',
                'tgl_masuk_sp', 'tgl_keluar', 'mulai_smt', 'smt_yudisium',
                'id_jns_daftar', 'id_jns_keluar', 'id_jalur_masuk', 'id_pembiayaan',
                'ipk', 'no_seri_ijazah', 'sk_yudisium', 'tgl_sk_yudisium', 
                'tgl_terbit_ijazah', 'judul_skripsi', 'sks_diakui',
                'last_update', 'last_sync', 'soft_delete', 'keterangan',
                'asal_data_ijazah'
            ]);
        });
    }
};
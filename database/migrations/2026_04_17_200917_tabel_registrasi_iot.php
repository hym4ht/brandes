<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel 'registrasi_iot'.
     * Tabel ini berfungsi sebagai penyimpanan sementara (buffer storage) untuk data
     * sidik jari dan PIN baru yang dikirim oleh modul IoT (ESP32), sebelum 
     * nantinya dikaitkan/mapped ke akun pengguna sistem.
     */
    public function up(): void
    {
        Schema::create('registrasi_iot', function (Blueprint $table) {
            // 1. Primary Key
            $table->id();

            // 2. Data Kredensial (IOT Credentials Data)
            $table->string('fingerprint_id', 50);                                    // Fingerprint slot ID recorded by sensor
            $table->string('pin', 6);                                                // 6-digit access PIN configured during registration phase

            // 3. Status Sinkronisasi/Pemetaan
            $table->boolean('is_used')->default(false);                              // Penanda status data (false = masih antre, true = sudah didaftarkan ke user)

            // 4. Metadata Waktu
            $table->timestamps();                                                    // Menyimpan waktu data diterima server (created_at) dan waktu dikaitkan (updated_at)
        });
    }

    /**
     * Membatalkan migration dengan menghapus tabel 'registrasi_iot'.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrasi_iot');
    }
};

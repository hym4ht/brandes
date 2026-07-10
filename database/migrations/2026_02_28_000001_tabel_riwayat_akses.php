<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel 'history_akses'.
     * Tabel ini digunakan untuk merekam seluruh riwayat log aktivitas (audit trail)
     * seperti siapa yang mencoba mengakses brankas, kapan, dan access status.
     */
    public function up(): void
    {
        Schema::create('history_akses', function (Blueprint $table) {
            // 1. Primary Key
            $table->id();

            // 2. Relasi & Snapshot Pengguna (User Relation)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Relation to users table
            $table->string('nama')->nullable();                                             // Name snapshot when activity occured

            // 3. Detail Aktivitas (Activity Details)
            $table->string('aktivitas');                                                    // Log description (e.g. 'Opening Safe', 'Failed Access', etc)
            $table->enum('status', ['Berhasil', 'Gagal'])->default('Berhasil');             // Final access status
            $table->string('fingerprint_id')->nullable();                                   // Fingerprint ID from sensor (if applicable)

            // 4. Metadata Waktu
            $table->timestamp('waktu')->useCurrent();                                       // Waktu spesifik terjadinya aktivitas (default: waktu sekarang)
            $table->timestamps();                                                           // Waktu record dibuat (created_at) dan diupdate (updated_at)
        });
    }

    /**
     * Membatalkan migration dengan menghapus tabel 'history_akses'.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_akses');
    }
};

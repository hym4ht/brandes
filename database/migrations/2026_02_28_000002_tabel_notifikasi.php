<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel 'notifikasi_keamanan'.
     * Tabel ini digunakan untuk menyimpan seluruh riwayat peringatan sistem (system alerts),
     * status perangkat, dan berbagai security information lainnya.
     */
    public function up(): void
    {
        Schema::create('notifikasi_keamanan', function (Blueprint $table) {
            // 1. Primary Key
            $table->id();

            // 2. Konten Notifikasi (Notification Content)
            $table->string('judul');                                                              // Notification title or alert type
            $table->text('pesan');                                                                // Full notification message detail

            // 3. Metadata & Status (Metadata & Status)
            $table->enum('tipe', ['warning', 'danger', 'info', 'success'])->default('info');      // Severity level for UI color matching
            $table->boolean('dibaca')->default(false);                                            // Read status (false: unread, true: read)

            // 4. Relasi Pengguna (Opsional)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();       // Relasi ke tabel users (null = notifikasi global untuk semua admin)

            // 5. Metadata Waktu
            $table->timestamp('waktu')->useCurrent();                                             // Waktu aktual saat notifikasi dihasilkan oleh sistem
            $table->timestamps();                                                                 // Waktu record dibuat (created_at) dan diupdate (updated_at)
        });
    }

    /**
     * Membatalkan migration dengan menghapus tabel 'notifikasi_keamanan'.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi_keamanan');
    }
};

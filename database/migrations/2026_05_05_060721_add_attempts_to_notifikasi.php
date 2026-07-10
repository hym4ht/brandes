<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk menambahkan fitur audit percobaan akses.
     * Kolom baru ini berfungsi untuk menyimpan data mentah dari alat IoT
     * saat terjadi percobaan akses oleh orang yang tidak dikenal (Unknown User).
     */
    public function up(): void
    {
        Schema::table('notifikasi_keamanan', function (Blueprint $table) {
            // Data percobaan akses (Access Attempt Data)
            // Disimpan untuk kebutuhan forensik jika user_id bernilai NULL
            $table->string('fingerprint_id_attempt', 50)->nullable()->after('user_id'); // ID sidik jari yang terbaca saat percobaan akses
            $table->string('pin_attempt', 6)->nullable()->after('fingerprint_id_attempt'); // PIN yang diketikkan saat percobaan akses
        });
    }

    /**
     * Membatalkan migration dengan menghapus kolom audit percobaan akses.
     */
    public function down(): void
    {
        Schema::table('notifikasi_keamanan', function (Blueprint $table) {
            $table->dropColumn(['fingerprint_id_attempt', 'pin_attempt']);
        });
    }
};

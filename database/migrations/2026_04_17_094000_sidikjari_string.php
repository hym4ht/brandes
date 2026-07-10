<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk mengubah tipe data kolom 'fingerprint_id'.
     *
     * Migration ini bertujuan untuk merevisi tipe data 'fingerprint_id'
     * pada tabel 'users' menjadi tipe string agar lebih fleksibel dalam menampung 
     * berbagai format ID sidik jari (misal jika format berubah menjadi alphanumeric).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Ubah Tipe Data ke String
            $table->string('fingerprint_id', 50)->nullable()->change(); // Memperluas kapasitas menjadi string (maks 50 karakter) & tetap nullable
        });
    }

    /**
     * Membatalkan migration dengan mengembalikan tipe data 'fingerprint_id' seperti semula.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Kembalikan Tipe Data ke Format Angka (Integer)
            $table->unsignedSmallInteger('fingerprint_id')->change();   // Mengembalikan ke tipe asal (small integer) saat proses rollback dipanggil
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk mengubah tipe data kolom 'fingerprint_id'.
     *
     * Migration ini bertujuan untuk menyesuaikan tipe data 'fingerprint_id'
     * pada tabel 'users' menjadi tipe angka (unsignedSmallInteger) agar 
     * kompatibel dengan batasan format slot ID dari sensor ESP32.
     */
    public function up(): void
    {
        // 1. Hapus Constraint Unique Lama
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['fingerprint_id']); // Unique index harus dilepas terlebih dahulu sebelum mengubah tipe kolom
        });

        // 2. Ubah Tipe Data & Pasang Kembali Constraint
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedSmallInteger('fingerprint_id')
                ->unique()
                ->nullable()
                ->change(); // Mengubah tipe menjadi small integer dan mengaktifkan kembali unique()
        });
    }

    /**
     * Membatalkan migration dengan mengembalikan tipe data 'fingerprint_id' seperti semula.
     */
    public function down(): void
    {
        // 1. Hapus Constraint Unique Saat Ini
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['fingerprint_id']);
        });

        // 2. Kembalikan Tipe Data ke Format Awal (String)
        Schema::table('users', function (Blueprint $table) {
            $table->string('fingerprint_id')
                ->unique()
                ->nullable()
                ->change(); // Mengembalikan ke tipe default (string) jika proses rollback dipanggil
        });
    }
};

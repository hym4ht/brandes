<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel 'users'.
     * Tabel ini digunakan untuk menyimpan seluruh kredensial dan
     * informasi pengguna yang memiliki akses ke sistem brankas.
     * Mengelola Authentication data, Profile, dan Role (admin/user).
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // 1. Primary Key
            $table->id();

            // 2. Informasi Dasar
            $table->string('nama');
            $table->string('username')->unique();

            // 3. Autentikasi (Security Credentials)
            $table->string('pin');                                                // PIN akses (stored in bcrypt hash or encrypted format)
            $table->unsignedSmallInteger('fingerprint_id')->unique()->nullable(); // Slot ID dari sensor biometrik ESP32 (range 1–127)

            // 4. Hak Akses & Status
            $table->enum('role', ['admin', 'user'])->default('user');             // Peran sistem (admin/user)
            $table->boolean('aktif')->default(true);                              // Status akun (true: aktif, false: nonaktif)

            // 5. Metadata Waktu
            $table->timestamps();                                                 // Menyimpan waktu pembuatan (created_at) dan pembaruan (updated_at)
        });
    }

    /**
     * Membatalkan migration dengan menghapus tabel 'users'.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Menjalankan proses seeding untuk tabel 'users'.
     *
     * Seeder ini berfungsi sebagai AKUN DARURAT sistem.
     * Dijalankan hanya saat:
     * - Instalasi pertama kali
     * - Database mengalami masalah dan perlu di-reset
     *
     * Setelah sistem berjalan, seluruh penambahan Admin/User
     * dilakukan melalui Dashboard Admin, BUKAN melalui seeder ini.
     */
    public function run(): void
    {
        // Kosongkan tabel (matikan FK sementara agar aman)
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        // Akun Admin Utama — M Zaeni (Super Admin / Pemilik Sistem)
        // Gunakan akun ini untuk login pertama kali dan membuat akun admin lainnya.
        User::create([
            'nama' => 'M Zaeni',
            'username' => 'zaeni',
            'pin' => Crypt::encryptString('150804'),
            'fingerprint_id' => null,
            'role' => 'admin',
            'aktif' => true,
            'is_superadmin' => true,
        ]);
    }
}

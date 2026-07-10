<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotifikasiKeamanan;
use App\Models\User;

class NotifikasiSeeder extends Seeder
{
    /**
     * Menjalankan proses seeding untuk tabel 'notifikasi_keamanan'.
     *
     * Seeder ini berfungsi untuk membuat sampel data (dummy) berupa berbagai 
     * skenario notifikasi keamanan (berhasil login, gagal sidik jari, peringatan sistem) 
     * guna memudahkan proses pengujian antarmuka dan fungsionalitas sistem.
     */
    public function run(): void
    {
        // 1. Dapatkan Referensi Pengguna (Target notifikasi spesifik)
        $user = User::where('role', 'user')->first();

        // Hentikan proses jika belum ada data user di dalam database
        if (!$user) {
            return;
        }

        // 2. Kumpulan Skenario Notifikasi (Mock Data)
        $notifikasi = [
            [
                'judul' => 'Akses Brankas',
                'pesan' => 'Anda berhasil membuka Brankas menggunakan autentikasi Fingerprints dan keypad PIN.',
                'tipe' => 'success',
                'user_id' => $user->id,
                'waktu' => now()->subHours(1),
            ],
            [
                'judul' => 'Autentikasi - Fingerprints gagal',
                'pesan' => 'Percobaan akses tidak dikenali. Pastikan jari Anda bersih dan kering saat melakukan autentikasi.',
                'tipe' => 'warning',
                'user_id' => $user->id,
                'waktu' => now()->subHours(2),
            ],
            [
                'judul' => 'Autentikasi - Keypad PIN gagal',
                'pesan' => 'Percobaan akses tidak valid. Pastikan Anda memasukkan PIN 6 digit yang benar.',
                'tipe' => 'warning',
                'user_id' => $user->id,
                'waktu' => now()->subHours(3),
            ],
            [
                'judul' => 'Akses Dashboard User',
                'pesan' => 'Percobaan akses sebanyak 3 kali berturut-turut dari akun Anda. Sistem keamanan telah diaktifkan. Hubungi admin jika ini bukan Anda.',
                'tipe' => 'danger',
                'user_id' => $user->id,
                'waktu' => now()->subHours(4),
            ],

            // Notifikasi Global/Sistem (Ditujukan untuk Admin, user_id = null)
            [
                'judul' => 'Peringatan - Upaya Paksa',
                'pesan' => 'Sistem mendeteksi upaya paksa membuka brankas dari pengguna yang tidak terdaftar.',
                'tipe' => 'danger',
                'user_id' => null,
                'waktu' => now()->subMinutes(30),
            ]
        ];

        // 3. Eksekusi Pemasukan Data (Insert) ke Database
        foreach ($notifikasi as $data) {
            NotifikasiKeamanan::create($data);
        }
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

/**
 * Controller untuk mengelola data profil dan indeks keamanan akun Pengguna (User).
 */
class UserController extends Controller
{
    /**
     * Menampilkan halaman kredensial user.
     * Menganalisis riwayat akses untuk menghitung indeks keamanan akun secara dinamis.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function kredensial()
    {
        // 1. Verifikasi Autentikasi (Hanya sesi user yang diizinkan mengakses)
        if (!session('user')) {
            return redirect()->route('login');
        }

        // 2. Pengambilan Data Profil & Riwayat Akses Terkait
        $user = User::with('histories')->find(session('user.id'));
        $history = $user->histories;

        // 3. Kalkulasi Statistik Aktivitas Akses
        $totalAttempts = $history->count();

        // 4. Logika Perhitungan Indeks Keamanan (Security Index): 
        // - Menggunakan nilai dari kolom security_index secara langsung
        // - Jika belum ada aktivitas akses = 0% (Status Netral)
        // - Jika skor mencapai 0 = Akun dinonaktifkan otomatis
        $securityIndex = $user->security_index;

        if ($totalAttempts === 0) {
            $securityIndex = 0;
            $securityMessage = "Belum ada aktivitas akses terdeteksi.";
        } elseif ($securityIndex === 0) {
            $securityMessage = "Keamanan Terdeteksi Lemah! Terlalu banyak percobaan gagal (Akses Diblokir).";
        } elseif ($securityIndex < 100) {
            $securityMessage = "Keamanan Terdeteksi Menurun! Ada percobaan gagal.";
        } else {
            $securityMessage = "Kebiasaan akses Anda sangat baik!";
        }

        // 5. Render Tampilan Kredensial dengan Skor Keamanan
        return view('user.kredensial', compact('user', 'securityIndex', 'securityMessage'));
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

/**
 * Controller untuk menangani proses autentikasi (Login & Logout) pengguna.
 */
class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     * Jika pengguna sudah login, arahkan ke dashboard sesuai role masing-masing.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLogin()
    {
        // 1. Cek keberadaan session user.id terlebih dahulu
        if (session('user.id')) {
            // Cek Sesi Admin (Auto-Redirect jika sudah login)
            if (in_array(session('user.role'), ['admin', 'superadmin'])) {
                $route = session('user.role') === 'superadmin' ? 'dashboard.superadmin' : 'dashboard.admin';
                return redirect()->route($route);
            }

            // Cek Sesi User (Auto-Redirect jika sudah login)
            if (session('user.role') === 'user') {
                return redirect()->route('dashboard.user');
            }
        }

        // 3. Tampilkan View Login Utama
        $availableRoles = [
            'superadmin' => 'Super Admin',
            'admin'       => 'Admin',
            'user'        => 'User'
        ];

        return view('login.login', compact('availableRoles'));
    }

    /**
     * Memproses percobaan login pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // 1. Validasi Input Login
        $request->validate([
            'username' => 'required|string',
            'pin' => 'required|string',
            'role' => 'required|in:admin,user,superadmin',
        ]);

        $username = trim($request->username);
        $role = $request->role;
        $pin = $request->pin;

        // 2. Cari User Berdasarkan Username (Case-Insensitive)
        $user = User::whereRaw('LOWER(username) = ?', [strtolower($username)])->first();

        // 3. Verifikasi Role secara Dinamis
        if ($user) {
            // Tentukan role asli user. Jika dia super admin, role aslinya 'superadmin'
            $actualRole = $user->is_superadmin ? 'superadmin' : $user->role;

            if ($role !== $actualRole) {
                $user = null; // Batalkan user jika role yang dipilih di form salah
            }
        }

        // 3. Verifikasi Keberadaan User dalam Database
        if (!$user) {
            return back()
                ->withInput($request->only('username', 'role'))
                ->withErrors(['login' => 'Username atau role tidak ditemukan di sistem.']);
        }

        // 4. Verifikasi Status Keaktifan Akun
        if (!$user->aktif) {
            return back()
                ->withInput($request->only('username', 'role'))
                ->withErrors(['login' => 'Akun Anda tidak aktif. Hubungi administrator.']);
        }

        // 5. Verifikasi Security Index (0% akibat riwayat gagal akses)
        if ($user->hasZeroSecurityIndex()) {
            return back()
                ->withInput($request->only('username', 'role'))
                ->withErrors(['login' => 'Akun Anda diblokir karena indeks keamanan 0%. Hubungi administrator.']);
        }

        // 6. Verifikasi PIN — Mendukung dua format:
        //    a) AES Encryption (data baru): dekripsi lalu bandingkan
        //    b) Bcrypt Hash (data lama): gunakan Hash::check() sebagai fallback
        $pinValid = false;
        try {
            // Coba dekripsi (untuk PIN yang disimpan dengan Crypt::encryptString)
            $pinValid = (Crypt::decryptString($user->pin) === $pin);
        } catch (DecryptException $e) {
            // Jika dekripsi gagal, berarti PIN lama disimpan dengan bcrypt
            $pinValid = Hash::check($pin, $user->pin);
        }

        if (!$pinValid) {
            return back()
                ->withInput($request->only('username', 'role'))
                ->withErrors(['login' => 'PIN yang Anda masukkan salah.']);
        }

        // 6. Simpan Data Identitas ke Dalam Sesi
        session([
            'user.id' => $user->id,
            'user.nama' => $user->nama,
            'user.username' => $user->username,
            'user.role' => $actualRole ?? $user->role,
            'user.fingerprint_id' => $user->fingerprint_id,
            'user.is_superadmin' => $user->is_superadmin,
            'user.updated_at' => $user->updated_at ? $user->updated_at->timestamp : 0,
        ]);

        // 7. Redireksi ke Dashboard Sesuai Hak Akses (Role)
        if (session('user.role') === 'superadmin') {
            return redirect()->route('dashboard.superadmin');
        } elseif (session('user.role') === 'admin') {
            return redirect()->route('dashboard.admin');
        } else {
            return redirect()->route('dashboard.user');
        }
    }

    /**
     * Memproses pengakhiran sesi (Logout) pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // 1. Hancurkan Data Sesi dan Perbarui Token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 2. Kembali ke Halaman Login Utama
        return redirect('/login');
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware untuk memproteksi akses khusus bagi Administrator.
 * Memastikan pengguna sudah login dan memiliki peran (role) sebagai admin.
 */
class AdminMiddleware
{
    /**
     * Menangani permintaan (request) yang masuk.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Verifikasi Keberadaan Sesi (Memastikan pengguna sudah login)
        if (!session('user.id')) {
            return redirect()->route('login');
        }

        // 2. Verifikasi Hak Akses (Memastikan role adalah administrator atau super admin)
        if (!in_array(session('user.role'), ['admin', 'superadmin'])) {
            return redirect()->route('dashboard.user')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        // 3. Lanjutkan Permintaan jika seluruh verifikasi lolos
        return $next($request);
    }
}

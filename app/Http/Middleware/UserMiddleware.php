<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware untuk memproteksi akses khusus bagi Pengguna Biasa (User).
 * Memastikan pengguna sudah login dan memiliki peran (role) sebagai user.
 */
class UserMiddleware
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

        // 2. Verifikasi Hak Akses (Memastikan role adalah user biasa)
        if (session('user.role') !== 'user') {
            $route = session('user.role') === 'superadmin' ? 'dashboard.superadmin' : 'dashboard.admin';
            return redirect()->route($route)
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        // 3. Lanjutkan Permintaan jika seluruh verifikasi lolos
        return $next($request);
    }
}

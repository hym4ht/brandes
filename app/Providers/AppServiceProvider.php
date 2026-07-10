<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\URL;

/**
 * Service Provider Utama Aplikasi.
 * Digunakan untuk melakukan bootstrapping layanan dan konfigurasi global framework.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Daftarkan (Register) layanan aplikasi apapun ke dalam Container.
     * Digunakan untuk binding interface ke implementasi.
     * 
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Inisialisasi (Bootstrap) layanan aplikasi apapun.
     * Dieksekusi setelah seluruh service provider lain terdaftar.
     * 
     * @return void
     */
    public function boot(): void
    {
        // IoT Logging Removed

        // Force HTTPS if not running on a local development host or if APP_ENV is not local
        $host = request()->getHost();
        $isLocalHost = in_array($host, ['localhost', '127.0.0.1', '::1']) || 
                       str_ends_with($host, '.test') || 
                       str_ends_with($host, '.local');

        $forced = false;
        if (!$isLocalHost || env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
            $forced = true;
        }

        \Log::info("HTTPS Force Check", [
            'host' => $host,
            'isLocalHost' => $isLocalHost,
            'env' => env('APP_ENV'),
            'forced' => $forced,
            'generated_url' => url()->to('/test-url-scheme')
        ]);

        // 1. Konfigurasi Atribut Tag Style untuk Vite Asset Manager
        // Digunakan untuk memastikan pelacakan perubahan aset (Turbo Track) saat reload.
        Vite::useStyleTagAttributes([
            'data-turbo-track' => 'reload',
        ]);
    }
}

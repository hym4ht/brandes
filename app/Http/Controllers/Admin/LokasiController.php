<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\LokasiBrankas;
use App\Models\LokasiHistory;

/**
 * Controller untuk mengelola data lokasi dan riwayat pergerakan brankas.
 */
class LokasiController extends Controller
{
    /**
     * Menampilkan halaman monitoring lokasi brankas.
     * Mengambil data brankas aktif dan riwayat pergerakannya.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // 1. Verifikasi Autentikasi (Hanya sesi user yang diizinkan mengakses)
        if (!session('user')) {
            return redirect()->route('login');
        }

        // 2. Inisialisasi Variabel Default
        $brankases = collect();
        $brankas = null;

        // 3. Ambil Data Brankas yang Berstatus Aktif
        try {
            $brankases = LokasiBrankas::where('aktif', true)->get();
            $brankas = LokasiBrankas::where('kode_brankas', 'BRX-001')->first()
                ?? $brankases->first()
                ?? LokasiBrankas::first();
        } catch (\Exception $e) {
            // Penanganan error jika data brankas gagal diambil
        }

        // 4. Ambil riwayat posisi GPS terbaru dari alat IoT
        $histories = $brankas
            ? LokasiHistory::with('brankas')
                ->where('lokasi_brankas_id', $brankas->id)
                ->orderByDesc('recorded_at')
                ->take(50)
                ->get()
            : collect();

        // 5. Render Tampilan dengan Data Pendukung
        return view('admin.lokasi', compact('brankases', 'brankas', 'histories'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models & Libraries
use App\Models\LokasiBrankas;
use App\Models\HistoryAkses;
use App\Models\NotifikasiKeamanan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama Dashboard Admin.
     * 
     * Berfungsi untuk menghitung dan merangkum statistik penggunaan harian, 
     * status brankas real-time, serta menampilkan log berkas terbaru.
     */
    public function index()
    {
        // 1. Dapatkan Data Master Brankas (Ambil kode BRX-001)
        $brankas = LokasiBrankas::where('kode_brankas', 'BRX-001')->first() ?? LokasiBrankas::first();

        // 2. Muat Data Riwayat & Notifikasi Terbaru
        $histories = HistoryAkses::with('user')->orderBy('waktu', 'desc')->take(5)->get();

        // Notifikasi Keamanan Terbaru (Tipe kritis/peringatan/akses)
        $notifications = NotifikasiKeamanan::with('user')->orderBy('waktu', 'desc')->get()->map(function ($n) {
            $uiType = 'akses';
            if ($n->tipe === 'warning') {
                $uiType = 'peringatan';
            } elseif ($n->tipe === 'danger') {
                $uiType = 'kritis';
            } elseif ($n->tipe === 'success') {
                $uiType = 'akses';
            }

            $meta = [];
            if ($n->user) {
                // Notifikasi terkait user terdaftar (akses berhasil / PIN salah)
                $userId = $n->user_id;
                $hash = md5($userId . 'brandes_salt');
                $suffix = chr(65 + (ord($hash[0]) % 26)) . (ord($hash[1]) % 10) . chr(65 + (ord($hash[2]) % 26));
                $fpId = str_pad($n->user->fingerprint_id ?? '0', 3, '0', STR_PAD_LEFT);
                $pinText = $n->tipe === 'success' ? 'PIN OK' : 'PIN SALAH';
                $meta = ["FP-{$fpId}-{$suffix}", $pinText, $n->user->nama];
            } elseif ($n->fingerprint_id_attempt) {
                // Notifikasi percobaan akses tidak dikenal (jari/ID tidak terdaftar)
                $fpText = 'FP-' . str_pad($n->fingerprint_id_attempt, 3, '0', STR_PAD_LEFT);
                $pinText = $n->pin_attempt ? 'PIN ' . $n->pin_attempt : 'Tidak Ada PIN';
                $meta = [$fpText, $pinText, 'Tidak Dikenal'];
            }
            // Notifikasi sistem (pembobolan, reset, GPS) tidak perlu meta

            return [
                'tipe' => $uiType,
                'judul' => $n->judul,
                'deskripsi' => $n->pesan,
                'waktu' => $n->waktu->format('Y-m-d H:i:s'),
                'meta' => $meta
            ];
        });

        // 3. Statistik Akses Hari Ini
        $totalAksesHariIni = HistoryAkses::whereDate('waktu', Carbon::today())->count();
        $statAkses = [
            'total' => $totalAksesHariIni,
            'class' => 'up',
            'trend' => 100,
            'label' => $totalAksesHariIni . ' aktivitas terdeteksi hari ini'
        ];

        // 4. Statistik Notifikasi Keamanan
        $totalNotifHariIni = NotifikasiKeamanan::whereDate('waktu', Carbon::today())->count();
        $statNotif = [
            'total' => $totalNotifHariIni,
            'class' => 'up',
            'trend' => 100,
            'label' => $totalNotifHariIni . ' notifikasi keamanan hari ini'
        ];

        // 5. Identifikasi Aktivitas Terakhir (Hanya Hari Ini)
        $lastAksesData = HistoryAkses::with('user')
            ->whereDate('waktu', Carbon::today())
            ->orderBy('waktu', 'desc')
            ->first();

        $lastAkses = [
            'name' => $lastAksesData ? ($lastAksesData->user->nama ?? $lastAksesData->nama ?? 'Unknown') : '-',
            'label' => $lastAksesData ? $lastAksesData->waktu->diffForHumans() : 'Tidak ada data akses hari ini'
        ];

        // 6. Render Tampilan
        return view('admin.dashboard', compact(
            'brankas',
            'statAkses',
            'statNotif',
            'lastAkses',
            'histories',
            'notifications'
        ));
    }
}

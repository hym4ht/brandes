<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\HistoryAkses;
use Illuminate\Http\Request;

/**
 * Controller untuk mengelola tampilan riwayat akses brankas bagi Admin dan User.
 */
class HistoryController extends Controller
{
    /**
     * Menampilkan halaman riwayat akses.
     */
    public function index()
    {
        // 1. Verifikasi Autentikasi
        $role = session('user.role');
        $userId = session('user.id');

        if (!in_array($role, ['admin', 'user', 'superadmin'])) {
            return redirect()->route('login');
        }

        // 2. Ambil Data Realtime dari Database & Olah Formatnya
        $historyQuery = HistoryAkses::query();

        if ($role === 'user') {
            $historyQuery->where('user_id', $userId);
        }

        $rawHistories = (clone $historyQuery)->orderBy('waktu', 'desc')->get();

        // Proses Agregasi Data (Group by Date + Identity)
        $histories = $this->aggregateHistories($rawHistories);

        // 3. Kalkulasi Rekapitulasi Statistik (Hari Ini)
        $today = \Carbon\Carbon::today();
        $totalAkses    = (clone $historyQuery)->whereDate('waktu', $today)->count();
        $aksesBerhasil = (clone $historyQuery)->whereDate('waktu', $today)->where('status', 'Berhasil')->count();
        $aksesGagal    = (clone $historyQuery)->whereDate('waktu', $today)->where('status', 'Gagal')->count();

        // 4. Render Tampilan
        if ($role === 'user') {
            return view('user.history', compact('histories', 'totalAkses', 'aksesBerhasil', 'aksesGagal'));
        }

        return view('admin.history', compact('histories', 'totalAkses', 'aksesBerhasil', 'aksesGagal'));
    }

    /**
     * API untuk mengambil statistik terbaru (Realtime).
     */
    public function stats()
    {
        $role = session('user.role');
        $userId = session('user.id');
        $today = \Carbon\Carbon::today();
        $historyQuery = HistoryAkses::query();

        if ($role === 'user') {
            $historyQuery->where('user_id', $userId);
        }

        return response()->json([
            'total'    => (clone $historyQuery)->whereDate('waktu', $today)->count(),
            'berhasil' => (clone $historyQuery)->whereDate('waktu', $today)->where('status', 'Berhasil')->count(),
            'gagal'    => (clone $historyQuery)->whereDate('waktu', $today)->where('status', 'Gagal')->count(),
        ]);
    }

    /**
     * Mengunduh rekap riwayat akses dalam format PDF (Sudah Ter-Agregasi).
     */
    public function download(Request $request)
    {
        $role = session('user.role');
        $userId = session('user.id');
        $search = $request->query('search');
        $date = $request->query('date');

        $query = HistoryAkses::query();

        // Jika user yang login bukan admin, batasi datanya sendiri
        if ($role === 'user') {
            $query->where('user_id', $userId);
        }

        // Filter Berdasarkan Pencarian Halaman
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('aktivitas', 'like', '%' . $search . '%');
            });
        }

        // Filter Berdasarkan Tanggal
        if ($date) {
            $query->whereDate('waktu', $date);
        }

        $rawHistories = $query->orderBy('waktu', 'desc')->get();

        // Jalankan fungsi pengelompokan agar data di PDF sama dengan di Web UI
        $histories = $this->aggregateHistories($rawHistories);

        // 2. Inisialisasi PdfGenerator kustom
        $pdf = new \App\Helpers\PdfGenerator(
            "History Akses Sistem Keamanan Brandes",
            ["NO", "NAMA", "AKTIVITAS", "METODE", "WAKTU", "TOTAL", "STATUS"],
            [30, 90, 140, 70, 95, 35, 55] // Total lebar disesuaikan untuk portrait A4
        );

        // 3. Masukkan data hasil agregasi ke baris tabel PDF
        foreach ($histories as $index => $history) {
            $pdf->addRow([
                $index + 1,
                $history['nama'] ?? '-',
                $history['aktivitas_bersih'] ?? $history['aktivitas'],
                $history['metode'] ?? 'Fingerprint',
                $history['waktu'] ? \Carbon\Carbon::parse($history['waktu'])->format('d-m-Y H:i:s') : '-',
                $history['total_akses'] . 'X',
                $history['status'] ?? 'Gagal'
            ]);
        }

        // 4. Bangun biner PDF menggunakan fungsi build()
        $pdfBinary = $pdf->build();
        $filename = "rekap-history-" . date('Ymd-His') . ".pdf";

        // 5. Kembalikan response berupa unduhan berkas PDF resmi
        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Helper Function untuk menyatukan logika agregasi data (DRY Principle).
     */
    private function aggregateHistories($rawHistories)
    {
        $groups = [];

        foreach ($rawHistories as $item) {
            $date = $item->waktu ? $item->waktu->format('Y-m-d') : date('Y-m-d');

            // Identity: prefer user_id, else use fingerprint identifier
            $identity = $item->user_id ? 'user:' . $item->user_id : 'fp:' . ($item->fingerprint_id ?? 'unknown');
            $key = $date . '|' . $identity;

            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'date' => $date,
                    'identity' => $identity,
                    'user_id_asli' => $item->user_id,
                    'fp_id' => $item->fingerprint_id,
                    'nama' => $item->nama,
                    'items' => [],
                    'failed_count_unknown_fp' => 0,
                ];
            }

            $groups[$key]['items'][] = $item;

            // Hitung percobaan gagal fingerprint tidak dikenal
            if (!$item->user_id && ($item->status === 'Gagal' || strtolower($item->status) === 'gagal')) {
                $groups[$key]['failed_count_unknown_fp']++;
            }
        }

        return collect(array_values($groups))->map(function ($g) {
            $latest = collect($g['items'])->sortByDesc(fn($i) => $i->waktu)->first();
            $totalAkses = count($g['items']);

            // Metode Deteksi
            $metode = collect($g['items'])->contains(function($i) {
                return str_contains((string)$i->aktivitas, 'PIN');
            }) ? 'Fingerprint + PIN' : 'Fingerprint';

            // Bersihkan teks aktivitas dari tanda kurung
            $aktivitas = trim(explode('(', ($latest->aktivitas ?? 'Akses Brankas'))[0]) ?: 'Akses Brankas';

            // Penentuan Status Keamanan Berdasarkan Logika Heuristik
            $hasSuccess = collect($g['items'])->contains(fn($i) => ($i->status ?? '') === 'Berhasil');

            if ($hasSuccess) {
                $status = 'Berhasil';
            } elseif ($g['failed_count_unknown_fp'] >= 3) {
                $status = 'Percobaan Pembobolan';
            } elseif ($g['failed_count_unknown_fp'] >= 1) {
                $status = 'Percobaan Akses';
            } else {
                $status = $latest->status ?? 'Gagal';
            }

            return [
                'id' => $latest->id,
                'user_id_asli' => $g['user_id_asli'],
                'fp_id' => $g['fp_id'],
                'nama' => $g['nama'],
                'aktivitas' => $aktivitas,
                'aktivitas_bersih' => $aktivitas,
                'metode' => $metode,
                'waktu' => $latest->waktu ? $latest->waktu->format('Y-m-d H:i:s') : null,
                'status' => $status,
                'total_akses' => $totalAkses,
            ];
        })->sortByDesc('waktu')->values();
    }
}

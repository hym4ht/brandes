<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotifikasiKeamanan;
use Illuminate\Support\Facades\Session;

/**
 * Controller untuk mengelola daftar notifikasi keamanan bagi Admin dan User.
 * Menangani filter data berdasarkan role dan mapping tipe notifikasi ke format UI.
 */
class NotifikasiController extends Controller
{
    /**
     * Menampilkan halaman daftar notifikasi.
     */
    public function index()
    {
        // 1. Verifikasi Autentikasi & Hak Akses
        $role = session('user.role');
        $userId = session('user.id');

        if (!in_array($role, ['admin', 'user', 'superadmin'])) {
            return redirect()->route('login');
        }

        // 2. Build Query
        $query = NotifikasiKeamanan::orderBy('waktu', 'desc');

        if ($role === 'user') {
            $query->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhereNull('user_id'); // Notifikasi sistem umum (global)
            });
        }

        $rawNotifikasi = $query->with('user')->get();

        // 3. Mapping Data (Tipe database ke Tipe Antarmuka/UI)
        $notifikasi = $rawNotifikasi->map(function ($notif) {
            $uiType = 'akses';

            if ($notif->tipe === 'warning') {
                $uiType = 'peringatan';
            } elseif ($notif->tipe === 'danger') {
                $uiType = 'kritis';
            } elseif ($notif->tipe === 'success') {
                $uiType = 'akses';
            }

            $meta = [];
            if ($notif->user) {
                $userIdTmp = $notif->user_id;
                $hash = md5($userIdTmp . 'brandes_salt');
                $suffix = chr(65 + (ord($hash[0]) % 26)) . (ord($hash[1]) % 10) . chr(65 + (ord($hash[2]) % 26));
                $fpId = str_pad($notif->user->fingerprint_id ?? '0', 3, '0', STR_PAD_LEFT);
                $pinText = $notif->tipe === 'success' ? 'PIN OK' : 'PIN SALAH';
                $meta = ["FP-{$fpId}-{$suffix}", $pinText, $notif->user->nama];
            } elseif ($notif->fingerprint_id_attempt) {
                $fpText = 'FP-' . str_pad($notif->fingerprint_id_attempt, 3, '0', STR_PAD_LEFT);
                $pinText = $notif->pin_attempt ? 'PIN ' . $notif->pin_attempt : 'Tidak Ada PIN';
                $meta = [$fpText, $pinText, 'Tidak Dikenal'];
            }

            return [
                'id' => $notif->id,
                'tipe' => $uiType,
                'judul' => $notif->judul,
                'deskripsi' => $notif->pesan,
                'meta' => $meta,
                'waktu' => $notif->waktu->format('Y-m-d H:i:s'),
                'dibaca' => $notif->dibaca,
            ];
        });

        // 4. Kalkulasi Statistik Notifikasi untuk Filter Tab di View
        $totalNotifikasi = $notifikasi->count();
        $totalPeringatan = $notifikasi->where('tipe', 'peringatan')->count();
        $totalKritis = $notifikasi->where('tipe', 'kritis')->count();
        $totalAkses = $notifikasi->where('tipe', 'akses')->count();

        // 5. Tentukan View Berdasarkan Role & Render Tampilan
        $view = in_array($role, ['admin', 'superadmin']) ? 'admin.notifikasi' : 'user.notifikasi';

        return view($view, compact(
            'notifikasi',
            'totalNotifikasi',
            'totalPeringatan',
            'totalKritis',
            'totalAkses'
        ));
    }

    /**
     * Mengunduh rekap notifikasi dalam format PDF.
     */
    public function download(Request $request)
    {
        $role   = session('user.role');
        $userId = session('user.id');

        if (!in_array($role, ['admin', 'user', 'superadmin'])) {
            return redirect()->route('login');
        }

        // 1. Ambil query notifikasi real-time dari database
        $query = NotifikasiKeamanan::orderBy('waktu', 'desc');

        if ($role === 'user') {
            $query->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhereNull('user_id');
            });
        }

        // 2. Ambil parameter filter dari request
        $search = $request->query('search');
        $date   = $request->query('date');
        $tab    = $request->query('tab', 'semua');

        // Filter berdasarkan tab/kategori notifikasi
        if ($tab !== 'semua') {
            if ($tab === 'kritis') {
                $query->where('tipe', 'danger');
            } elseif ($tab === 'peringatan') {
                $query->where('tipe', 'warning');
            } elseif ($tab === 'akses') {
                $query->where('tipe', 'success');
            }
        }

        // Filter berdasarkan tanggal
        if ($date) {
            $query->whereDate('waktu', $date);
        }

        $rawNotifikasi = $query->with('user')->get();

        // 3. Mapping data dan pencarian kata kunci
        $data = [];
        $idx = 1;

        foreach ($rawNotifikasi as $notif) {
            $uiType = 'akses';

            if ($notif->tipe === 'warning') {
                $uiType = 'peringatan';
            } elseif ($notif->tipe === 'danger') {
                $uiType = 'kritis';
            } elseif ($notif->tipe === 'success') {
                $uiType = 'akses';
            }

            $meta = '';

            if ($notif->user) {
                $userIdTmp = $notif->user_id;
                $hash = md5($userIdTmp . 'brandes_salt');
                $suffix = chr(65 + (ord($hash[0]) % 26)) . (ord($hash[1]) % 10) . chr(65 + (ord($hash[2]) % 26));
                $fpId = str_pad($notif->user->fingerprint_id ?? '0', 3, '0', STR_PAD_LEFT); // Ditambahkan Fallback ?? '0' agar aman
                $pinText = $notif->tipe === 'success' ? 'PIN OK' : 'PIN SALAH';

                $meta = "FP-{$fpId}-{$suffix} | {$pinText} | " . $notif->user->nama;
            } else if ($notif->fingerprint_id_attempt || $notif->pin_attempt) {
                $fpText = $notif->fingerprint_id_attempt
                    ? "FP-" . str_pad($notif->fingerprint_id_attempt, 3, '0', STR_PAD_LEFT)
                    : "FP-XXX-XXXX";

                $pinText = $notif->pin_attempt
                    ? "PIN " . $notif->pin_attempt
                    : "XXX-XXX";

                $meta = "{$fpText} | {$pinText} | Unknown User";
            } else {
                if ($uiType === 'peringatan' || $uiType === 'kritis') {
                    $meta = 'FP-XXX-XXXX | XXX-XXX | Unknown User';
                } else {
                    $meta = 'FP-001-A2F3 | 150804 | Sodikin';
                }
            }

            $pesan = $notif->pesan;
            $judul = $notif->judul;
            $waktu = $notif->waktu ? $notif->waktu->format('Y-m-d H:i:s') : date('Y-m-d H:i:s');

            // Filter keyword jika pencarian aktif
            if ($search) {
                $searchLower = strtolower($search);

                $match = str_contains(strtolower((string)$judul), $searchLower) ||
                         str_contains(strtolower((string)$uiType), $searchLower) ||
                         str_contains(strtolower((string)$pesan), $searchLower) ||
                         str_contains(strtolower((string)$waktu), $searchLower) ||
                         str_contains(strtolower((string)$meta), $searchLower);

                if (!$match) {
                    continue;
                }
            }

            $data[] = [
                $idx++,
                ucfirst($uiType),
                $judul,
                $pesan . ($meta ? " ($meta)" : ""),
                $waktu
            ];
        }

        // 4. Inisialisasi generator PDF
        $pdfGenerator = new \App\Helpers\PdfGenerator(
            'REKAPITULASI NOTIFIKASI KEAMANAN',
            ['No', 'Tipe', 'Judul', 'Deskripsi / Detail Aktivitas', 'Waktu'],
            [30, 65, 110, 205, 105]
        );

        foreach ($data as $row) {
            $pdfGenerator->addRow($row);
        }

        $pdfBinary = $pdfGenerator->build();
        $filename = "rekap-notifikasi-" . date('Ymd-His') . ".pdf";

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}

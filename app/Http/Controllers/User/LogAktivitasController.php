<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;

class LogAktivitasController extends Controller
{
    /**
     * Menampilkan halaman daftar log berkas untuk user.
     */
    public function index()
    {
        $logs = LogAktivitas::orderBy('created_at', 'desc')->get();
        $totalLogs = $logs->count();

        $totalKtp = $logs->filter(fn($l) => !empty($l->file_ktp) && $l->status_ktp !== 'Diambil')->count();
        $totalKk = $logs->filter(fn($l) => !empty($l->file_kk) && $l->status_kk !== 'Diambil')->count();
        $totalAkte = $logs->filter(fn($l) => !empty($l->file_akte) && $l->status_akte !== 'Diambil')->count();
        $totalBerkas = $totalKtp + $totalKk + $totalAkte;

        return view('user.log-aktivitas', compact('logs', 'totalLogs', 'totalKtp', 'totalKk', 'totalAkte', 'totalBerkas'));
    }

    /**
     * Memproses pengambilan dokumen KK oleh user.
     */
    public function ambilDokumen(Request $request, $id)
    {
        $jenis = $request->input('jenis'); // ktp, kk, atau akte

        $log = LogAktivitas::findOrFail($id);

        $statusBaru = '';
        if ($jenis == 'ktp') {
            $log->status_ktp = ($log->status_ktp === 'Diambil') ? 'Tersedia' : 'Diambil';
            $statusBaru = $log->status_ktp;
        } elseif ($jenis == 'kk') {
            $log->status_kk = ($log->status_kk === 'Diambil') ? 'Tersedia' : 'Diambil';
            $statusBaru = $log->status_kk;
        } elseif ($jenis == 'akte') {
            $log->status_akte = ($log->status_akte === 'Diambil') ? 'Tersedia' : 'Diambil';
            $statusBaru = $log->status_akte;
        }

        $log->save();

        $msg = ($statusBaru === 'Diambil')
            ? "Dokumen " . strtoupper($jenis) . " berhasil diambil!"
            : "Dokumen " . strtoupper($jenis) . " dikembalikan (Tersedia kembali).";

        return redirect()->route('user.log.aktivitas')->with('success', $msg);
    }
}


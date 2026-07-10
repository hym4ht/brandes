<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogBerkas;
use App\Models\NotifikasiKeamanan;

class LogBerkasController extends Controller
{
    /**
     * Menampilkan halaman daftar log berkas untuk user.
     */
    public function index()
    {
        $logs = LogBerkas::orderBy('created_at', 'desc')->get();
        $totalLogs = $logs->count();

        $totalKtp = $logs->filter(fn($l) => !empty($l->file_ktp) && $l->status_ktp !== 'Diambil')->count();
        $totalKk = $logs->filter(fn($l) => !empty($l->file_kk) && $l->status_kk !== 'Diambil')->count();
        $totalAkte = $logs->filter(fn($l) => !empty($l->file_akte) && $l->status_akte !== 'Diambil')->count();
        $totalBerkas = $totalKtp + $totalKk + $totalAkte;

        return view('user.log-berkas', compact('logs', 'totalLogs', 'totalKtp', 'totalKk', 'totalAkte', 'totalBerkas'));
    }

    /**
     * Memproses pengambilan dokumen KK oleh user.
     */
    public function ambilDokumen(Request $request, $id)
    {
        $jenis = $request->input('jenis'); // ktp, kk, atau akte

        $log = LogBerkas::findOrFail($id);

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
            
        $userName = session('user.nama', 'User');
        $judulNotif = ($statusBaru === 'Diambil') ? "Pengambilan Dokumen" : "Pengembalian Dokumen";
        $namaPemilik = $log->ktp_nama ?? 'N/A';
        $pesanNotif = "Dokumen " . strtoupper($jenis) . " atas nama " . $namaPemilik . " telah " . ($statusBaru === 'Diambil' ? "diambil" : "dikembalikan") . " oleh " . $userName . ".";
        
        NotifikasiKeamanan::create([
            'judul' => $judulNotif,
            'pesan' => $pesanNotif,
            'tipe' => 'success',
            'dibaca' => false,
            'user_id' => session('user.id'),
            'waktu' => now()
        ]);
        
        return redirect()->route('user.log.berkas')->with('success', $msg);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Storage;

class LogAktivitasController extends Controller
{
    /**
     * Menampilkan halaman daftar log berkas.
     */
    public function index()
    {
        $logs = LogAktivitas::orderBy('created_at', 'desc')->get();
        
        $totalLogs = $logs->count();
        
        $totalKtp = $logs->filter(fn($l) => !empty($l->file_ktp) && $l->status_ktp !== 'Diambil')->count();
        $totalKk = $logs->filter(fn($l) => !empty($l->file_kk) && $l->status_kk !== 'Diambil')->count();
        $totalAkte = $logs->filter(fn($l) => !empty($l->file_akte) && $l->status_akte !== 'Diambil')->count();
        $totalBerkas = $totalKtp + $totalKk + $totalAkte;

        return view('admin.log-aktivitas', compact('logs', 'totalLogs', 'totalKtp', 'totalKk', 'totalAkte', 'totalBerkas'));
    }

    /**
     * Menyimpan data log berkas baru dari form (Modal).
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'judul' => 'required|string',
            'ktp_nik' => 'required|string|max:16',
            'ktp_nama' => 'required|string',
            'kk_no' => 'required|string|max:16',
            'kk_nama_kepala' => 'required|string',
            'akte_no' => 'required|string',
            'file_ktp' => 'required|mimes:pdf|max:2048',
            'file_kk' => 'required|mimes:pdf|max:2048',
            'file_akte' => 'required|mimes:pdf|max:2048',
        ]);

        $fileKtpPath = null;
        $fileKkPath = null;
        $fileAktePath = null;

        if ($request->hasFile('file_ktp')) {
            $fileKtpPath = $request->file('file_ktp')->store('dokumen', 'public');
        }
        if ($request->hasFile('file_kk')) {
            $fileKkPath = $request->file('file_kk')->store('dokumen', 'public');
        }
        if ($request->hasFile('file_akte')) {
            $fileAktePath = $request->file('file_akte')->store('dokumen', 'public');
        }

        LogAktivitas::create([
            'judul' => $request->judul,
            'ktp_nik' => $request->ktp_nik,
            'ktp_nama' => $request->ktp_nama,
            'kk_no' => $request->kk_no,
            'kk_nama_kepala' => $request->kk_nama_kepala,
            'akte_no' => $request->akte_no,
            'file_ktp' => $fileKtpPath,
            'file_kk' => $fileKkPath,
            'file_akte' => $fileAktePath,
            'status_ktp' => 'Tersedia',
            'status_kk' => 'Tersedia',
            'status_akte' => 'Tersedia',
        ]);

        return redirect()->route('log.aktivitas')->with('success', 'Data Log Berkas berhasil ditambahkan!');
    }

    /**
     * Memperbarui data log berkas (Modal Edit).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string',
            'ktp_nik' => 'required|string|max:16',
            'ktp_nama' => 'required|string',
            'kk_no' => 'required|string|max:16',
            'kk_nama_kepala' => 'required|string',
            'akte_no' => 'required|string',
            'file_ktp' => 'nullable|mimes:pdf|max:2048',
            'file_kk' => 'nullable|mimes:pdf|max:2048',
            'file_akte' => 'nullable|mimes:pdf|max:2048',
        ]);

        $log = LogAktivitas::findOrFail($id);

        $dataUpdate = [
            'judul' => $request->judul,
            'ktp_nik' => $request->ktp_nik,
            'ktp_nama' => $request->ktp_nama,
            'kk_no' => $request->kk_no,
            'kk_nama_kepala' => $request->kk_nama_kepala,
            'akte_no' => $request->akte_no,
        ];

        // Jika ada upload file baru, hapus yang lama dan simpan yang baru
        if ($request->hasFile('file_ktp')) {
            if ($log->file_ktp && Storage::disk('public')->exists($log->file_ktp)) {
                Storage::disk('public')->delete($log->file_ktp);
            }
            $dataUpdate['file_ktp'] = $request->file('file_ktp')->store('dokumen', 'public');
        }

        if ($request->hasFile('file_kk')) {
            if ($log->file_kk && Storage::disk('public')->exists($log->file_kk)) {
                Storage::disk('public')->delete($log->file_kk);
            }
            $dataUpdate['file_kk'] = $request->file('file_kk')->store('dokumen', 'public');
        }

        if ($request->hasFile('file_akte')) {
            if ($log->file_akte && Storage::disk('public')->exists($log->file_akte)) {
                Storage::disk('public')->delete($log->file_akte);
            }
            $dataUpdate['file_akte'] = $request->file('file_akte')->store('dokumen', 'public');
        }

        $log->update($dataUpdate);

        return redirect()->route('log.aktivitas')->with('success', 'Data Log Berkas berhasil diperbarui!');
    }

    /**
     * Menghapus data log berkas (Modal Delete).
     */
    public function destroy($id)
    {
        $log = LogAktivitas::findOrFail($id);
        
        // Hapus file-file terkait
        if ($log->file_ktp && Storage::disk('public')->exists($log->file_ktp)) {
            Storage::disk('public')->delete($log->file_ktp);
        }
        if ($log->file_kk && Storage::disk('public')->exists($log->file_kk)) {
            Storage::disk('public')->delete($log->file_kk);
        }
        if ($log->file_akte && Storage::disk('public')->exists($log->file_akte)) {
            Storage::disk('public')->delete($log->file_akte);
        }

        $log->delete();

        // Karena ini dipanggil via AJAX dari delete.js, kembalikan JSON
        return response()->json([
            'success' => true,
            'message' => 'Data Log Berkas berhasil dihapus!'
        ]);
    }
}


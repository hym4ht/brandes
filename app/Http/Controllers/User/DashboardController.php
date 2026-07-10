<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\LokasiBrankas;

class DashboardController extends Controller
{
    public function index()
    {
        $brankas = LokasiBrankas::first();
        $userId = session('user.id');
        
        $rawHistories = \App\Models\HistoryAkses::where('user_id', $userId)
            ->orderBy('waktu', 'desc')
            ->take(5)
            ->get();
            
        $histories = $rawHistories->map(function($item) use ($userId) {
            $aktivitas = (string) ($item->aktivitas ?? 'Akses Brankas');
            $metode = 'Fingerprint';
            if (str_contains($aktivitas, 'PIN')) {
                $metode = 'Fingerprint + PIN';
            }

            $aktivitasBersih = trim(explode('(', $aktivitas)[0]) ?: 'Akses Brankas';

            // Hitung total akses user pada tanggal record ini
            $totalAksesOnDay = 0;
            if ($item->waktu) {
                $totalAksesOnDay = \App\Models\HistoryAkses::where('user_id', $userId)
                    ->whereDate('waktu', $item->waktu->toDateString())
                    ->count();
            }

            return [
                'id'               => $item->id,
                'user_id_asli'     => $item->user_id,
                'fp_id'            => $item->fingerprint_id,
                'nama'             => $item->nama,
                'aktivitas'        => $aktivitasBersih,
                'aktivitas_bersih' => $aktivitasBersih,
                'metode'           => $metode,
                'waktu'            => $item->waktu?->format('Y-m-d H:i:s'),
                'waktu_lalu'       => $item->waktu?->diffForHumans() ?? '-',
                'status'           => $item->status,
                'total_akses'      => $totalAksesOnDay,
            ];
        });

        $totalAksesHariIni = \App\Models\HistoryAkses::where('user_id', $userId)
            ->whereDate('waktu', \Carbon\Carbon::today())
            ->count();

        $lastAksesData = \App\Models\HistoryAkses::where('user_id', $userId)
            ->orderBy('waktu', 'desc')
            ->first();
            
        $lastAkses = $lastAksesData ? $lastAksesData->waktu->diffForHumans() : '-';

        return view('user.dashboard', compact('brankas', 'histories', 'totalAksesHariIni', 'lastAkses'));
    }
}

<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\HistoryAkses;
use App\Models\NotifikasiKeamanan;
use Illuminate\Support\Facades\DB;

/**
 * Controller untuk menangani upaya akses brankas (Autentikasi Perangkat ke Web).
 * Mengatur riwayat akses, sistem notifikasi, dan keamanan pemblokiran akun otomatis.
 */
class AksesController extends Controller
{
    /**
     * Menangani upaya (attempt) akses dari perangkat IoT.
     * Menerima payload dari hardware dan menentukan apakah akses tersebut berhasil atau gagal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleAttempt(Request $request)
    {
        return response()->json(['message' => 'IoT endpoint disabled'], 403);
    }

    private function handleSuccess($user, $method)
    {
        // IoT Logic Removed
    }

    private function handleFailure($user, $method)
    {
        // IoT Logic Removed
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendingRegistration;
use App\Models\User;
use App\Models\HistoryAkses;
use App\Models\LokasiBrankas;
use App\Models\LokasiHistory;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * Controller untuk menangani API komunikasi antara ESP32 (Hardware) dan Server (Software).
 */
class IotController extends Controller
{
    /**
     * Endpoint: POST /api/iot/register
     * Deskripsi: Menerima data pendaftaran sidik jari dan PIN baru dari alat IoT.
     *            Data disimpan ke tabel sementara (buffer) sebelum difinalisasi oleh Admin.
     */
    public function register(Request $request)
    {
        try {
            // Log data yang masuk untuk debugging
            Log::info('IOT Register Request: ', $request->all());

            // Cek batas maksimal 8 user aktif
            $jumlahUser = User::where('aktif', true)->count();
            if ($jumlahUser >= 8) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kapasitas penuh! Maksimal 8 user terdaftar.',
                    'total' => $jumlahUser,
                ], 403);
            }

            $reg = PendingRegistration::create([
                'fingerprint_id' => $request->fingerprint_id,
                'pin' => $request->pin,
                'is_used' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data pendaftaran diterima di server.',
                'data' => $reg
            ], 201);

        } catch (\Exception $e) {
            Log::error('IOT Register Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Endpoint: POST /api/iot/verify
     * Deskripsi: Memverifikasi upaya akses brankas menggunakan sidik jari dan PIN.
     */
    public function verify(Request $request)
    {
        try {
            $rawId = $request->fingerprint_id;

            // Jika dari alat mengirim ID 0 berarti 3x Gagal Akses Ilegal
            if ($rawId == 0) {
                Log::warning("Akses Ilegal Terdeteksi dari Alat IoT.");
                HistoryAkses::create([
                    'user_id' => null,
                    'nama' => 'Tidak Dikenal',
                    'aktivitas' => 'Percobaan Akses Ilegal (3x Jari Salah)',
                    'status' => 'Gagal',
                    'fingerprint_id' => 0,
                    'waktu' => now(),
                ]);

                \App\Models\NotifikasiKeamanan::create([
                    'judul'                 => 'Akses Ilegal (3x Gagal)',
                    'pesan'                 => 'Terdeteksi 3x percobaan sidik jari yang salah berturut-turut pada alat. Harap periksa brankas Anda.',
                    'tipe'                  => 'warning',
                    'dibaca'                => false,
                    'fingerprint_id_attempt' => '0',
                    'waktu'                 => now()
                ]);

                return response()->json(['success' => false, 'message' => 'Ilegal Logged.'], 401);
            }

            $formattedId = 'FP-' . str_pad($rawId, 3, '0', STR_PAD_LEFT);
            $user = User::where(function ($query) use ($rawId, $formattedId) {
                $query->where('fingerprint_id', $rawId)
                    ->orWhere('fingerprint_id', $formattedId);
            })->first();

            if (!$user) {
                HistoryAkses::create([
                    'user_id' => null,
                    'nama' => 'Tidak Dikenal',
                    'aktivitas' => 'Akses Gagal (Jari Tidak Terdaftar: ' . $rawId . ')',
                    'status' => 'Gagal',
                    'fingerprint_id' => $rawId,
                    'waktu' => now(),
                ]);

                \App\Models\NotifikasiKeamanan::create([
                    'judul'                  => 'Jari Tidak Dikenal',
                    'pesan'                  => "Ada upaya membuka brankas dengan sidik jari (ID: {$rawId}) yang belum terdaftar atau belum disetujui.",
                    'tipe'                   => 'warning',
                    'dibaca'                 => false,
                    'fingerprint_id_attempt' => (string) $rawId,
                    'waktu'                  => now()
                ]);

                return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
            }

            if (!$user->aktif) {
                HistoryAkses::create([
                    'user_id' => $user->id,
                    'nama' => $user->nama,
                    'aktivitas' => 'Akses Ditolak (Akun Nonaktif)',
                    'status' => 'Gagal',
                    'fingerprint_id' => $rawId,
                    'waktu' => now(),
                ]);

                \App\Models\NotifikasiKeamanan::create([
                    'judul'                  => 'Akses Ditolak (Akun Nonaktif)',
                    'pesan'                  => "{$user->nama} mencoba membuka brankas, namun akses ditolak karena akun dinonaktifkan.",
                    'tipe'                   => 'danger',
                    'user_id'                => $user->id,
                    'dibaca'                 => false,
                    'fingerprint_id_attempt' => (string) $rawId,
                    'waktu'                  => now()
                ]);

                return response()->json(['success' => false, 'message' => 'Akun dinonaktifkan.'], 403);
            }

            // 1.5. Verifikasi Security Index (Jika 0% karena pernah gagal, tolak akses)
            if ($user->hasZeroSecurityIndex()) {
                HistoryAkses::create([
                    'user_id' => $user->id,
                    'nama' => $user->nama,
                    'aktivitas' => 'Akses Ditolak (Indeks Keamanan 0%)',
                    'status' => 'Gagal',
                    'fingerprint_id' => $rawId,
                    'waktu' => now(),
                ]);

                \App\Models\NotifikasiKeamanan::create([
                    'judul'                  => 'Akses Diblokir',
                    'pesan'                  => "{$user->nama} mencoba membuka brankas, namun akses ditolak karena indeks keamanannya 0%.",
                    'tipe'                   => 'danger',
                    'user_id'                => $user->id,
                    'dibaca'                 => false,
                    'fingerprint_id_attempt' => (string) $rawId,
                    'waktu'                  => now()
                ]);

                return response()->json(['success' => false, 'message' => 'Akses Diblokir (Security 0%).'], 403);
            }

            // 2. Verifikasi PIN
            $isAuthorized = false;
            $catatan = 'Membuka Brankas (Sidik Jari)';

            if ($request->has('skip_pin') && $request->skip_pin == true) {
                $isAuthorized = true;
            } else {
                try {
                    $decryptedPin = \Illuminate\Support\Facades\Crypt::decryptString($user->pin);
                    $isAuthorized = ($decryptedPin === $request->pin);
                    $catatan = 'Membuka Brankas (Sidik Jari + PIN)';
                } catch (\Exception $e) {
                    // Fallback
                }
            }

            // 3. Pencatatan Akhir
            if ($isAuthorized) {
                if ($user->security_index < 100) {
                    $user->security_index = 100;
                    $user->save();
                }

                HistoryAkses::create([
                    'user_id' => $user->id,
                    'nama' => $user->nama,
                    'aktivitas' => $catatan . ' (PIN: ' . $request->pin . ')',
                    'status' => 'Berhasil',
                    'fingerprint_id' => $user->fingerprint_id,
                    'waktu' => now(),
                ]);

                \App\Models\NotifikasiKeamanan::create([
                    'judul' => 'Brankas Terbuka',
                    'pesan' => "Brankas diakses sukses oleh {$user->nama} (ID: {$user->fingerprint_id}) menggunakan PIN: {$request->pin}.",
                    'tipe' => 'success',
                    'user_id' => $user->id,
                    'dibaca' => false,
                    'waktu' => now()
                ]);

                return response()->json(['success' => true, 'message' => 'Akses Diberikan.', 'user' => $user->nama]);
            } else {
                $user->security_index = max(0, $user->security_index - 20);
                if ($user->security_index <= 0) {
                    $user->aktif = false;

                    \App\Models\NotifikasiKeamanan::create([
                        'judul'   => 'Akun Dinonaktifkan Otomatis',
                        'pesan'   => "Akun {$user->nama} dinonaktifkan secara otomatis karena terdeteksi 5 kali gagal memasukkan PIN.",
                        'tipe'    => 'danger',
                        'user_id' => $user->id,
                        'dibaca'  => false,
                        'waktu'   => now()
                    ]);
                }
                $user->save();

                HistoryAkses::create([
                    'user_id' => $user->id,
                    'nama' => $user->nama,
                    'aktivitas' => 'Gagal Membuka (PIN Salah)',
                    'status' => 'Gagal',
                    'fingerprint_id' => $request->fingerprint_id,
                    'waktu' => now(),
                ]);

                \App\Models\NotifikasiKeamanan::create([
                    'judul' => 'PIN Salah',
                    'pesan' => "{$user->nama} menempelkan jari dengan benar, namun memasukkan PIN yang salah.",
                    'tipe' => 'warning',
                    'user_id' => $user->id,
                    'dibaca' => false,
                    'waktu' => now()
                ]);

                return response()->json(['success' => false, 'message' => 'Akses Ditolak.'], 401);
            }

        } catch (\Exception $e) {
            Log::error('IOT Verify Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Endpoint: POST /api/iot/gps
     * Deskripsi: Memperbarui data lokasi dan metrik GPS dari brankas.
     */
    public function updateGps(Request $request)
    {
        try {
            $request->validate([
                'kode_brankas' => 'required|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'altitude' => 'nullable|numeric',
                'hdop' => 'nullable|numeric',
                'satellites' => 'nullable|integer',
                'speed_kmh' => 'nullable|numeric',
                'fix_quality' => 'nullable|integer',
                'getaran' => 'nullable|numeric',
                'raw_nmea' => 'nullable|string',
            ]);

            $brankas = LokasiBrankas::where('kode_brankas', $request->kode_brankas)->first();

            if (!$brankas) {
                return response()->json(['success' => false, 'message' => 'Brankas tidak ditemukan.'], 404);
            }

            $hasCoordinates = $request->filled('latitude') && $request->filled('longitude');
            $gpsPayload = [
                'is_online' => 1,
                'last_seen' => now(),
                'hdop' => $request->input('hdop', $brankas->hdop ?? 0),
                'satellites' => $request->input('satellites', $brankas->satellites ?? 0),
                'speed_kmh' => $request->input('speed_kmh', $brankas->speed_kmh ?? 0),
                'fix_quality' => $request->input('fix_quality', $hasCoordinates ? 1 : 0),
                'last_gps_update' => now(),
            ];

            if ($request->has('altitude')) {
                $gpsPayload['altitude'] = $request->altitude;
            }

            if ($hasCoordinates) {
                $gpsPayload['latitude'] = $request->latitude;
                $gpsPayload['longitude'] = $request->longitude;
            }

            $brankas->update($gpsPayload);

            if ($hasCoordinates) {
                LokasiHistory::create([
                    'lokasi_brankas_id' => $brankas->id,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'altitude' => $request->altitude,
                    'hdop' => $request->hdop,
                    'satellites' => $request->satellites,
                    'fix_quality' => $gpsPayload['fix_quality'],
                    'speed_kmh' => $request->speed_kmh,
                    'getaran' => $request->getaran,
                    'status' => LokasiHistory::determineStatus($request->getaran, $request->speed_kmh),
                    'raw_nmea' => $request->raw_nmea,
                    'recorded_at' => now(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => $hasCoordinates
                    ? 'Data GPS diperbarui.'
                    : 'Status GPS diterima, menunggu koordinat valid.',
                'gps_valid' => $hasCoordinates,
                'kode_brankas' => $brankas->kode_brankas,
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payload GPS tidak valid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('IOT GPS Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui GPS.'], 500);
        }
    }

    /**
     * Endpoint: POST /api/iot/heartbeat
     * Deskripsi: Sinyal rutin dari alat untuk menandakan brankas sedang "Online"
     *            dan melaporkan kondisi solenoid (terkunci/terbuka).
     */
    public function heartbeat(Request $request)
    {
        try {
            $kode = $request->kode_brankas;
            $pintu = $this->normalizeStatusPintu($request->status_pintu);

            $brankas = LokasiBrankas::where('kode_brankas', $kode)->first();

            if (!$brankas) {
                return response()->json(['success' => false, 'message' => 'Brankas tidak terdaftar'], 404);
            }

            // Update status & pintu sesuai ENUM database
            $brankas->status = ($pintu == 'TERBUKA') ? 'terbuka' : 'aman';
            $brankas->status_pintu = $pintu;
            $brankas->is_online = 1; // Gunakan kolom is_online
            $brankas->last_seen = now();
            $brankas->aktif = true;

            $brankas->save();

            return response()->json([
                'success' => true,
                'message' => 'Heartbeat OK',
                'is_online' => 1,
                'status' => $brankas->status,
                'status_pintu' => $brankas->status_pintu,
                'last_seen' => $brankas->last_seen->format('Y-m-d H:i:s'),
            ]);

        } catch (\Exception $e) {
            Log::error('IOT Heartbeat Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Endpoint: POST /api/iot/alert
     * Deskripsi: Menerima peringatan bahaya/pembobolan dari sensor getaran alat IoT.
     */
    public function alert(Request $request)
    {
        try {
            $kode = $request->kode_brankas ?? 'Unknown';

            \App\Models\NotifikasiKeamanan::create([
                'judul' => 'BAHAYA PEMBOBOLAN DETEKSI!',
                'pesan' => "Sensor getaran pada brankas ({$kode}) mendeteksi guncangan keras. Kemungkinan ada upaya pembobolan paksa.",
                'tipe' => 'danger',
                'dibaca' => false,
                'waktu' => now()
            ]);

            return response()->json(['success' => true, 'message' => 'Peringatan bahaya berhasil dicatat.']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Endpoint: POST /api/iot/reset
     * Deskripsi: Sinkronisasi ketika alat IoT menghapus seluruh memorinya (Menu C).
     *            Ini akan menghapus seluruh user (selain admin) dan riwayat pendaftaran.
     */
    public function resetMemory(Request $request)
    {
        try {
            // Hapus semua pengguna reguler (Jangan hapus Admin!)
            $deletedUsers = User::where('role', 'user')->delete();

            // Kosongkan tabel pendaftaran sementara
            \App\Models\PendingRegistration::truncate();

            // Buat Notifikasi Keamanan
            \App\Models\NotifikasiKeamanan::create([
                'judul' => 'Sistem Di-Reset (Format Memori)',
                'pesan' => "Alat IoT ({$request->kode_brankas}) melakukan format ulang memori sidik jari. Sebanyak {$deletedUsers} data pengguna telah dihapus dari database web agar tetap sinkron.",
                'tipe' => 'warning',
                'dibaca' => false,
                'waktu' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Database tersinkronisasi. Semua data jari telah dihapus.'
            ]);

        } catch (\Exception $e) {
            Log::error('IOT Reset Memory Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Endpoint: GET /api/iot/status
     * Deskripsi: Digunakan oleh Dashboard untuk mendapatkan status terkini brankas.
     */
    public function getStatus()
    {
        $brankas = LokasiBrankas::where('kode_brankas', 'BRX-001')->first();

        if (!$brankas) {
            return response()->json(['success' => false], 404);
        }

        // --- KONEKSI DARI HEARTBEAT TERAKHIR ---
        $onlineStatus = (bool) $brankas->is_online
            && (!$brankas->last_seen || $brankas->last_seen->gt(now()->subSeconds(15)));

        // --- STATISTIK REAL-TIME ---
        $totalAksesHariIni = \App\Models\HistoryAkses::whereDate('waktu', \Carbon\Carbon::today())->count();
        $totalNotifHariIni = \App\Models\NotifikasiKeamanan::whereDate('waktu', \Carbon\Carbon::today())->count();
        $lastAksesData = \App\Models\HistoryAkses::with('user')
            ->whereDate('waktu', \Carbon\Carbon::today())
            ->orderBy('waktu', 'desc')
            ->first();

        $lastAksesName = $lastAksesData ? ($lastAksesData->user->nama ?? $lastAksesData->nama ?? 'Unknown') : '-';
        $lastAksesLabel = $lastAksesData ? $lastAksesData->waktu->diffForHumans() : 'Tidak ada data akses hari ini';

        return response()->json([
            'success' => true,
            'is_online' => (int) $onlineStatus,
            'kode_brankas' => $brankas->kode_brankas,
            'nama_brankas' => $brankas->nama_brankas,
            'lokasi' => $brankas->lokasi,
            'status' => $brankas->status,
            'status_pintu' => $brankas->status_pintu,
            'satellites' => $brankas->satellites,
            'hdop' => $brankas->hdop,
            'latitude' => $brankas->latitude,
            'longitude' => $brankas->longitude,
            'last_seen' => $brankas->last_seen ? $brankas->last_seen->format('Y-m-d H:i:s') : '-',
            'last_gps_update' => $brankas->last_gps_update ? $brankas->last_gps_update->format('Y-m-d H:i:s') : '-',
            // Data tambahan untuk dashboard
            'stat_akses' => $totalAksesHariIni,
            'stat_notif' => $totalNotifHariIni,
            'last_akses_name' => $lastAksesName,
            'last_akses_label' => $lastAksesLabel,
        ]);
    }

    private function normalizeStatusPintu(?string $status): string
    {
        $normalized = strtoupper(trim((string) $status));

        return match ($normalized) {
            'TERBUKA', 'UNLOCKED', 'OPEN', 'BUKA' => 'TERBUKA',
            default => 'TERKUNCI',
        };
    }

    /**
     * Endpoint: GET /api/iot/latest-registration
     * Deskripsi: Digunakan oleh Frontend Dashboard untuk mengecek apakah ada data
     *            pendaftaran baru dari alat yang belum diproses.
     */
    public function getLatestRegistration()
    {
        $latest = PendingRegistration::where('is_used', false)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($latest) {
            return response()->json([
                'success' => true,
                'data' => $latest
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Tidak ada antrean pendaftaran.'], 404);
    }
}


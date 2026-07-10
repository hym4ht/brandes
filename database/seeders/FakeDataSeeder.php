<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class FakeDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::transaction(function () use ($now) {
            $this->cleanupPreviousFakeData();

            $users = $this->seedUsers($now);
            $brankases = $this->seedBrankas($now);

            $this->seedPendingRegistrations($now);
            $this->seedHistoryAkses($users, $now);
            $this->seedNotifikasi($users, $now);
            $this->seedLokasiHistory($brankases, $now);
        });
    }

    private function cleanupPreviousFakeData(): void
    {
        $fakeUsers = User::whereIn('username', array_column($this->users(), 'username'))->get();
        $fakeUserIds = $fakeUsers->pluck('id')->all();
        $fakeNames = $fakeUsers
            ->pluck('nama')
            ->merge(collect($this->users())->pluck('nama'))
            ->push('Tidak Dikenal')
            ->unique()
            ->values()
            ->all();
        $fakeFingerprints = collect($this->users())
            ->pluck('fingerprint_id')
            ->filter(fn($fingerprintId) => $fingerprintId !== null)
            ->merge(['0', '7', '8', '9', '88', '99'])
            ->unique()
            ->values()
            ->all();

        DB::table('history_akses')
            ->where(function ($query) use ($fakeNames, $fakeFingerprints) {
                $query->whereIn('nama', $fakeNames)
                    ->orWhereIn('fingerprint_id', $fakeFingerprints);
            })
            ->delete();

        $notificationQuery = DB::table('notifikasi_keamanan')
            ->whereIn('judul', $this->notificationTitles());

        if ($fakeUserIds !== []) {
            $notificationQuery->orWhereIn('user_id', $fakeUserIds);
        }

        $notificationQuery->delete();

        DB::table('registrasi_iot')
            ->whereIn('fingerprint_id', ['7', '8', '9'])
            ->delete();

        $brankasIds = DB::table('lokasi_brankas')
            ->whereIn('kode_brankas', $this->brankasCodes())
            ->pluck('id');

        if ($brankasIds->isNotEmpty()) {
            DB::table('lokasi_history')
                ->whereIn('lokasi_brankas_id', $brankasIds)
                ->delete();
        }

        DB::table('lokasi_brankas')
            ->whereIn('kode_brankas', $this->brankasCodes())
            ->delete();
    }

    private function seedUsers(Carbon $now): array
    {
        $users = [];

        foreach ($this->users() as $data) {
            $pin = $data['pin'];
            unset($data['pin']);

            $users[$data['username']] = User::updateOrCreate(
                ['username' => $data['username']],
                array_merge($data, [
                    'pin' => Crypt::encryptString($pin),
                    'is_superadmin' => false,
                    'updated_at' => $now,
                ])
            )->fresh();
        }

        return $users;
    }

    private function seedBrankas(Carbon $now): array
    {
        DB::table('lokasi_brankas')->insert([
            [
                'nama_brankas' => 'Brankas Utama',
                'kode_brankas' => 'BRX-001',
                'lokasi' => 'Ruang Keuangan Lantai 2',
                'latitude' => -6.20000000,
                'longitude' => 106.81666600,
                'altitude' => 18.40,
                'hdop' => 0.82,
                'satellites' => 10,
                'speed_kmh' => 0.00,
                'fix_quality' => 1,
                'last_gps_update' => $now->copy()->subMinutes(2),
                'status' => 'aman',
                'status_pintu' => 'TERKUNCI',
                'is_online' => true,
                'last_seen' => $now->copy()->subSeconds(45),
                'keterangan' => 'Unit utama untuk simulasi dashboard realtime.',
                'aktif' => true,
                'created_at' => $now->copy()->subDays(14),
                'updated_at' => $now->copy()->subSeconds(45),
            ],
            [
                'nama_brankas' => 'Brankas Arsip',
                'kode_brankas' => 'BRX-002',
                'lokasi' => 'Ruang Arsip Lantai 1',
                'latitude' => -6.20185000,
                'longitude' => 106.81942000,
                'altitude' => 17.90,
                'hdop' => 1.18,
                'satellites' => 8,
                'speed_kmh' => 0.12,
                'fix_quality' => 1,
                'last_gps_update' => $now->copy()->subMinutes(9),
                'status' => 'terbuka',
                'status_pintu' => 'TERBUKA',
                'is_online' => true,
                'last_seen' => $now->copy()->subMinutes(1),
                'keterangan' => 'Unit simulasi dengan pintu sedang terbuka.',
                'aktif' => true,
                'created_at' => $now->copy()->subDays(10),
                'updated_at' => $now->copy()->subMinutes(1),
            ],
            [
                'nama_brankas' => 'Brankas Cadangan',
                'kode_brankas' => 'BRX-003',
                'lokasi' => 'Gudang Inventaris',
                'latitude' => -6.19876000,
                'longitude' => 106.81298000,
                'altitude' => 19.20,
                'hdop' => 2.75,
                'satellites' => 4,
                'speed_kmh' => 0.00,
                'fix_quality' => 0,
                'last_gps_update' => $now->copy()->subHours(3),
                'status' => 'peringatan',
                'status_pintu' => 'TERKUNCI',
                'is_online' => false,
                'last_seen' => $now->copy()->subHours(3),
                'keterangan' => 'Unit nonaktif untuk menguji state offline/peringatan.',
                'aktif' => false,
                'created_at' => $now->copy()->subDays(7),
                'updated_at' => $now->copy()->subHours(3),
            ],
        ]);

        return DB::table('lokasi_brankas')
            ->whereIn('kode_brankas', $this->brankasCodes())
            ->pluck('id', 'kode_brankas')
            ->all();
    }

    private function seedPendingRegistrations(Carbon $now): void
    {
        DB::table('registrasi_iot')->insert([
            [
                'fingerprint_id' => '7',
                'pin' => '777777',
                'is_used' => false,
                'created_at' => $now->copy()->subMinutes(18),
                'updated_at' => $now->copy()->subMinutes(18),
            ],
            [
                'fingerprint_id' => '8',
                'pin' => '888888',
                'is_used' => false,
                'created_at' => $now->copy()->subHours(1),
                'updated_at' => $now->copy()->subHours(1),
            ],
            [
                'fingerprint_id' => '9',
                'pin' => '999999',
                'is_used' => true,
                'created_at' => $now->copy()->subDays(1),
                'updated_at' => $now->copy()->subHours(20),
            ],
        ]);
    }

    private function seedHistoryAkses(array $users, Carbon $now): void
    {
        $rows = [];

        foreach ($this->historyRows() as $row) {
            $user = $row['username'] ? $users[$row['username']] : null;

            $rows[] = [
                'user_id' => $user?->id,
                'nama' => $user?->nama ?? $row['nama'],
                'aktivitas' => $row['aktivitas'],
                'status' => $row['status'],
                'fingerprint_id' => $row['fingerprint_id'] ?? $user?->fingerprint_id,
                'waktu' => $now->copy()->subMinutes($row['minutes_ago']),
                'created_at' => $now->copy()->subMinutes($row['minutes_ago']),
                'updated_at' => $now->copy()->subMinutes($row['minutes_ago']),
            ];
        }

        DB::table('history_akses')->insert($rows);
    }

    private function seedNotifikasi(array $users, Carbon $now): void
    {
        $notifications = [
            [
                'judul' => 'Brankas Terbuka',
                'pesan' => "Brankas diakses sukses oleh Budi Santoso (ID: 1) menggunakan PIN: 111111.",
                'tipe' => 'success',
                'dibaca' => false,
                'user_id' => $users['demo_budi']->id,
                'waktu' => $now->copy()->subMinutes(12),
            ],
            [
                'judul' => 'PIN Salah',
                'pesan' => "Siti Aminah menempelkan jari dengan benar, namun memasukkan PIN yang salah.",
                'tipe' => 'warning',
                'dibaca' => false,
                'user_id' => $users['demo_siti']->id,
                'waktu' => $now->copy()->subMinutes(34),
            ],
            [
                'judul' => 'Jari Tidak Dikenal',
                'pesan' => 'Ada upaya membuka brankas dengan sidik jari (ID: 88) yang belum terdaftar atau belum disetujui.',
                'tipe' => 'warning',
                'dibaca' => false,
                'user_id' => null,
                'fingerprint_id_attempt' => '88',
                'pin_attempt' => '000000',
                'waktu' => $now->copy()->subHours(2),
            ],
            [
                'judul' => 'Akses Ilegal (3x Gagal)',
                'pesan' => 'Terdeteksi 3x percobaan sidik jari yang salah berturut-turut pada alat. Harap periksa brankas Anda.',
                'tipe' => 'warning',
                'dibaca' => false,
                'user_id' => null,
                'fingerprint_id_attempt' => '0',
                'waktu' => $now->copy()->subHours(2)->subMinutes(35),
            ],
            [
                'judul' => 'BAHAYA PEMBOBOLAN DETEKSI!',
                'pesan' => 'Sensor getaran pada brankas (BRX-003) mendeteksi guncangan keras. Kemungkinan ada upaya pembobolan paksa.',
                'tipe' => 'danger',
                'dibaca' => false,
                'user_id' => null,
                'waktu' => $now->copy()->subHours(3),
            ],
            [
                'judul' => 'GPS Diperbarui',
                'pesan' => 'Koordinat Brankas Utama berhasil diperbarui dan akurasi GPS dalam kondisi baik.',
                'tipe' => 'info',
                'dibaca' => true,
                'user_id' => null,
                'waktu' => $now->copy()->subHours(5),
            ],
            [
                'judul' => 'Akun Dinonaktifkan',
                'pesan' => 'Akun Rina Lestari sedang nonaktif setelah beberapa percobaan akses gagal.',
                'tipe' => 'danger',
                'dibaca' => true,
                'user_id' => $users['demo_rina']->id,
                'waktu' => $now->copy()->subDay(),
            ],
        ];

        $rows = array_map(function (array $notification) use ($now) {
            return array_merge([
                'fingerprint_id_attempt' => null,
                'pin_attempt' => null,
                'created_at' => $notification['waktu'] ?? $now,
                'updated_at' => $notification['waktu'] ?? $now,
            ], $notification);
        }, $notifications);

        DB::table('notifikasi_keamanan')->insert($rows);
    }

    private function seedLokasiHistory(array $brankases, Carbon $now): void
    {
        $rows = [];

        foreach ($this->lokasiHistoryRows() as $row) {
            $rows[] = [
                'lokasi_brankas_id' => $brankases[$row['kode_brankas']],
                'latitude' => $row['latitude'],
                'longitude' => $row['longitude'],
                'altitude' => $row['altitude'],
                'hdop' => $row['hdop'],
                'satellites' => $row['satellites'],
                'fix_quality' => $row['fix_quality'],
                'speed_kmh' => $row['speed_kmh'],
                'status' => $row['status'],
                'raw_nmea' => $row['raw_nmea'],
                'recorded_at' => $now->copy()->subMinutes($row['minutes_ago']),
                'created_at' => $now->copy()->subMinutes($row['minutes_ago']),
                'updated_at' => $now->copy()->subMinutes($row['minutes_ago']),
            ];
        }

        DB::table('lokasi_history')->insert($rows);
    }

    private function users(): array
    {
        return [
            [
                'nama' => 'Admin Operasional',
                'username' => 'demo_admin_ops',
                'pin' => '123456',
                'fingerprint_id' => null,
                'role' => 'admin',
                'aktif' => true,
                'security_index' => 100,
            ],
            [
                'nama' => 'Admin Audit',
                'username' => 'demo_admin_audit',
                'pin' => '654321',
                'fingerprint_id' => null,
                'role' => 'admin',
                'aktif' => false,
                'security_index' => 100,
            ],
            [
                'nama' => 'Budi Santoso',
                'username' => 'demo_budi',
                'pin' => '111111',
                'fingerprint_id' => '1',
                'role' => 'user',
                'aktif' => true,
                'security_index' => 100,
            ],
            [
                'nama' => 'Siti Aminah',
                'username' => 'demo_siti',
                'pin' => '222222',
                'fingerprint_id' => '2',
                'role' => 'user',
                'aktif' => true,
                'security_index' => 72,
            ],
            [
                'nama' => 'Andi Pratama',
                'username' => 'demo_andi',
                'pin' => '333333',
                'fingerprint_id' => '3',
                'role' => 'user',
                'aktif' => true,
                'security_index' => 88,
            ],
            [
                'nama' => 'Maya Putri',
                'username' => 'demo_maya',
                'pin' => '444444',
                'fingerprint_id' => '4',
                'role' => 'user',
                'aktif' => true,
                'security_index' => 95,
            ],
            [
                'nama' => 'Rina Lestari',
                'username' => 'demo_rina',
                'pin' => '555555',
                'fingerprint_id' => '5',
                'role' => 'user',
                'aktif' => false,
                'security_index' => 0,
            ],
            [
                'nama' => 'Dimas Saputra',
                'username' => 'demo_dimas',
                'pin' => '666666',
                'fingerprint_id' => '6',
                'role' => 'user',
                'aktif' => false,
                'security_index' => 40,
            ],
        ];
    }

    private function historyRows(): array
    {
        return [
            [
                'username' => 'demo_budi',
                'nama' => null,
                'aktivitas' => 'Membuka Brankas (Sidik Jari + PIN)',
                'status' => 'Berhasil',
                'minutes_ago' => 12,
            ],
            [
                'username' => 'demo_maya',
                'nama' => null,
                'aktivitas' => 'Membuka Brankas (Sidik Jari)',
                'status' => 'Berhasil',
                'minutes_ago' => 24,
            ],
            [
                'username' => 'demo_siti',
                'nama' => null,
                'aktivitas' => 'Gagal Membuka (PIN Salah)',
                'status' => 'Gagal',
                'minutes_ago' => 34,
            ],
            [
                'username' => 'demo_andi',
                'nama' => null,
                'aktivitas' => 'Membuka Brankas (Sidik Jari + PIN)',
                'status' => 'Berhasil',
                'minutes_ago' => 58,
            ],
            [
                'username' => null,
                'nama' => 'Tidak Dikenal',
                'aktivitas' => 'Akses Gagal (Jari Tidak Terdaftar: 88)',
                'status' => 'Gagal',
                'fingerprint_id' => '88',
                'minutes_ago' => 130,
            ],
            [
                'username' => null,
                'nama' => 'Tidak Dikenal',
                'aktivitas' => 'Percobaan Akses Ilegal (3x Jari Salah)',
                'status' => 'Gagal',
                'fingerprint_id' => '0',
                'minutes_ago' => 185,
            ],
            [
                'username' => 'demo_budi',
                'nama' => null,
                'aktivitas' => 'Membuka Brankas (Sidik Jari + PIN)',
                'status' => 'Berhasil',
                'minutes_ago' => 1440,
            ],
            [
                'username' => 'demo_rina',
                'nama' => null,
                'aktivitas' => 'Gagal Membuka (PIN Salah)',
                'status' => 'Gagal',
                'minutes_ago' => 1600,
            ],
        ];
    }

    private function lokasiHistoryRows(): array
    {
        return [
            [
                'kode_brankas' => 'BRX-001',
                'latitude' => -6.20002000,
                'longitude' => 106.81664000,
                'altitude' => 18.30,
                'hdop' => 0.88,
                'satellites' => 10,
                'fix_quality' => 1,
                'speed_kmh' => 0.00,
                'status' => 'normal',
                'raw_nmea' => '$GPGGA,021200.00,0612.0012,S,10648.9984,E,1,10,0.88,18.3,M,0.0,M,,*52',
                'minutes_ago' => 2,
            ],
            [
                'kode_brankas' => 'BRX-001',
                'latitude' => -6.20004000,
                'longitude' => 106.81662000,
                'altitude' => 18.40,
                'hdop' => 0.95,
                'satellites' => 9,
                'fix_quality' => 1,
                'speed_kmh' => 0.05,
                'status' => 'normal',
                'raw_nmea' => '$GPGGA,020900.00,0612.0024,S,10648.9972,E,1,09,0.95,18.4,M,0.0,M,,*5A',
                'minutes_ago' => 8,
            ],
            [
                'kode_brankas' => 'BRX-002',
                'latitude' => -6.20185000,
                'longitude' => 106.81942000,
                'altitude' => 17.90,
                'hdop' => 1.18,
                'satellites' => 8,
                'fix_quality' => 1,
                'speed_kmh' => 0.12,
                'status' => 'normal',
                'raw_nmea' => '$GPGGA,015700.00,0612.1110,S,10649.1652,E,1,08,1.18,17.9,M,0.0,M,,*49',
                'minutes_ago' => 18,
            ],
            [
                'kode_brankas' => 'BRX-003',
                'latitude' => -6.19876000,
                'longitude' => 106.81298000,
                'altitude' => 19.20,
                'hdop' => 2.75,
                'satellites' => 4,
                'fix_quality' => 0,
                'speed_kmh' => 0.00,
                'status' => 'bahaya',
                'raw_nmea' => '$GPGGA,232000.00,0611.9256,S,10648.7788,E,0,04,2.75,19.2,M,0.0,M,,*6B',
                'minutes_ago' => 180,
            ],
        ];
    }

    private function notificationTitles(): array
    {
        return [
            'Brankas Terbuka',
            'PIN Salah',
            'Jari Tidak Dikenal',
            'Akses Ilegal (3x Gagal)',
            'BAHAYA PEMBOBOLAN DETEKSI!',
            'GPS Diperbarui',
            'Akun Dinonaktifkan',
            'Sistem Di-Reset (Format Memori)',
        ];
    }

    private function brankasCodes(): array
    {
        return ['BRX-001', 'BRX-002', 'BRX-003'];
    }
}

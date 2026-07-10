<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\PendingRegistration;

/**
 * Controller untuk mengelola data Pengguna (User).
 */
class UserController extends Controller
{
    /**
     * Menampilkan halaman Manajemen User.
     * Mengambil seluruh data user dengan role 'user' dan menghitung rekapitulasi statusnya.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // 1. Verifikasi Autentikasi (Hanya sesi Admin yang diizinkan mengakses)
        if (!session('user') || !in_array(session('user.role'), ['admin', 'superadmin'])) {
            return redirect()->route('login');
        }

        // 2. Pengambilan Data & Kalkulasi Statistik
        $users        = User::get()->filter(fn($user) => $user->role === 'user' && !$user->is_superadmin)->values();
        $totalUser    = $users->count();
        $userAktif    = $users->where('aktif', true)->count();
        $userNonaktif = $users->where('aktif', false)->count();

        // 3. Render Tampilan dengan Data Pendukung
        return view('admin.list-user', compact('users', 'totalUser', 'userAktif', 'userNonaktif'));
    }

    /**
     * Menyimpan data User baru ke dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Formulir
        $request->validate([
            'nama'           => 'required|string|max:255',
            'username'       => 'required|string|max:255|unique:users,username',
            'pin'            => 'required|numeric|digits:6',
            'fingerprint_id' => 'nullable|string|max:50|unique:users,fingerprint_id',
            'role'           => 'required|in:user',
        ]);

        // 2. Eksekusi Penambahan Data User
        $user = User::create([
            'nama'           => $request->nama,
            'username'       => $request->username,
            'pin'            => Crypt::encryptString($request->pin),
            'fingerprint_id' => $request->fingerprint_id ?: null,
            'role'           => $request->role,
            'aktif'          => true,
        ]);

        // 3. Jika menggunakan data dari alat (IoT), tandai data tersebut sudah digunakan
        if ($request->fingerprint_id) {
            PendingRegistration::where('fingerprint_id', $request->fingerprint_id)
                               ->where('is_used', false)
                               ->update(['is_used' => true]);
        }

        return redirect()->route('user.list')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Memperbarui detail profil User yang sudah ada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // 1. Cari Target User
        $user = User::findOrFail($id);

        // 2. Validasi Input (Username dan Fingerprint mengabaikan ID milik user ini sendiri)
        $request->validate([
            'nama'           => 'required|string|max:255',
            'username'       => 'required|string|max:255|unique:users,username,' . $id,
            'pin'            => 'nullable|numeric|digits:6',
            'fingerprint_id' => 'nullable|string|max:50|unique:users,fingerprint_id,' . $id,
            'aktif'          => 'boolean',
        ]);

        $newStatus = $request->boolean('aktif', true);

        // 3. Susun Objek Pembaruan Data
        $data = [
            'nama'           => $request->nama,
            'username'       => $request->username,
            'fingerprint_id' => $request->fingerprint_id ?: null,
            'aktif'          => $newStatus,
        ];

        // Jika sebelumnya nonaktif dan sekarang diaktifkan, reset skor keamanan ke 100
        if ($newStatus && !$user->aktif) {
            $data['security_index'] = 100;
        }

        // 4. Proses PIN hanya jika diisi oleh pengguna
        if ($request->filled('pin')) {
            $data['pin'] = Crypt::encryptString($request->pin);
        }

        // 5. Simpan Perubahan
        $user->update($data);

        return redirect()->route('user.list')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Menghapus data User secara permanen.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        // Respons untuk antarmuka yang menggunakan request AJAX (Fetch API)
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus.'
            ]);
        }

        // Respons Fallback untuk request standard
        return redirect()->route('user.list')->with('success', 'User berhasil dihapus.');
    }

    /**
     * Mengubah status aktif/nonaktif dari seorang User secara dinamis.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function toggleStatus($id)
    {
        // 1. Cari Target User & Balikkan Status Aktifnya
        $user = User::findOrFail($id);
        $newStatus = !$user->aktif;
        
        $updateData = ['aktif' => $newStatus];
        if ($newStatus) {
            $updateData['security_index'] = 100;
        }
        
        $user->update($updateData);

        // 2. Respons untuk Antarmuka yang menggunakan AJAX (Toggle Switch)
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'aktif'   => $user->aktif,
                'nama'    => $user->nama,
            ]);
        }

        // 3. Respons Fallback untuk Request Standard
        $status = $user->aktif ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('user.list')->with('success', "User {$user->nama} berhasil {$status}.");
    }
}

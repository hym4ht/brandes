<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk merepresentasikan tabel 'notifikasi_keamanan'.
 * Menyimpan data pemberitahuan keamanan bagi pengguna (alert akses, status bahaya, dll).
 */
class NotifikasiKeamanan extends Model
{
    /**
     * Nama tabel yang dikaitkan dengan model ini.
     *
     * @var string
     */
    protected $table = 'notifikasi_keamanan';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'judul',
        'pesan',
        'tipe',
        'dibaca',
        'user_id',
        'waktu',
    ];

    /**
     * Atribut yang harus dikonversi ke tipe data tertentu (casting).
     *
     * @var array
     */
    protected $casts = [
        'dibaca' => 'boolean',
        'waktu'  => 'datetime',
    ];

    /**
     * Mendapatkan data pengguna yang menerima notifikasi ini.
     * Relasi: Many-to-One (BelongsTo)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

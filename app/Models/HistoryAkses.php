<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk merepresentasikan tabel 'history_akses'.
 * Digunakan untuk mencatat setiap aktivitas upaya pembukaan brankas.
 */
class HistoryAkses extends Model
{
    /**
     * Nama tabel yang dikaitkan dengan model ini.
     *
     * @var string
     */
    protected $table = 'history_akses';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'nama',
        'aktivitas',
        'status',
        'waktu',
        'fingerprint_id',
    ];

    /**
     * Atribut yang harus dikonversi ke tipe data tertentu (casting).
     *
     * @var array
     */
    protected $casts = [
        'waktu' => 'datetime',
    ];

    /**
     * Mendapatkan data pengguna yang terkait dengan riwayat akses ini.
     * Relasi: Many-to-One (BelongsTo)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

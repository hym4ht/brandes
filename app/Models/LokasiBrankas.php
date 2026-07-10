<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk merepresentasikan tabel 'lokasi_brankas'.
 * Mengelola data master brankas, status koneksi IoT, dan metrik GPS Neo-6M.
 *
 * Kolom GPS Utama:
 * - latitude / longitude  : Koordinat posisi terkini.
 * - altitude              : Ketinggian dari permukaan laut (meter).
 * - hdop                  : Horizontal Dilution of Precision (Akurasi horizontal).
 * - satellites            : Jumlah satelit yang terhubung.
 * - speed_kmh             : Kecepatan pergerakan brankas (km/h).
 * - fix_quality           : Kualitas sinyal GPS (0=invalid, 1=fix, 2=DGPS).
 */
class LokasiBrankas extends Model
{
    /**
     * Nama tabel yang dikaitkan dengan model ini.
     *
     * @var string
     */
    protected $table = 'lokasi_brankas';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'nama_brankas',
        'lokasi',
        'kode_brankas',
        'status',
        'status_pintu',
        'is_online',
        'last_seen',
        'keterangan',
        'aktif',
        'latitude',
        'longitude',
        'altitude',
        'hdop',
        'satellites',
        'speed_kmh',
        'fix_quality',
        'last_gps_update',
    ];

    /**
     * Atribut yang harus dikonversi ke tipe data tertentu (casting).
     *
     * @var array
     */
    protected $casts = [
        'aktif'           => 'boolean',
        'is_online'       => 'boolean',
        'latitude'        => 'decimal:8',
        'longitude'       => 'decimal:8',
        'altitude'        => 'decimal:2',
        'hdop'            => 'decimal:2',
        'satellites'      => 'integer',
        'speed_kmh'       => 'decimal:2',
        'fix_quality'     => 'integer',
        'last_gps_update' => 'datetime',
        'last_seen'       => 'datetime',
    ];
}

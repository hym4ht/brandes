<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';

    protected $fillable = [
        'judul',
        'ktp_nik',
        'ktp_nama',
        'kk_no',
        'kk_nama_kepala',
        'akte_no',
        'file_ktp',
        'file_kk',
        'file_akte',
        'status_ktp',
        'status_kk',
        'status_akte'
    ];
}

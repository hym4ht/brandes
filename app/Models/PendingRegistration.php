<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PendingRegistration extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang dikelola oleh model ini.
     * @var string
     */
    protected $table = 'registrasi_iot';

    /**
     * Atribut yang tidak boleh diisi secara massal.
     * @var array
     */
    protected $guarded = [];
}

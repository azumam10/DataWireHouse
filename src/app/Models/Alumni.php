<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasFactory;

   
    protected $table = 'alumni';

    protected $fillable = [
        'nama',
        'nim',
        'fakultas_id',
        'tahun_lulus',
        'pekerjaan',
        'email',
        'no_telepon',
        'alamat',
        'status_pekerjaan',
    ];
}

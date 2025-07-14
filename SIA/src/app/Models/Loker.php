<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loker extends Model
{
    use HasFactory;

    protected $table = 'lokers';
    protected $fillable = [
        'judul_loker', 'deskripsi', 'perusahaan', 'lokasi',
        'tipe', 'tanggal_berakhir'
    ];

    public function distribusiManual()
    {
        return $this->hasMany(LokerDistribusiManual::class);
    }
}

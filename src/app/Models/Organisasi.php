<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organisasi extends Model
{
    use HasFactory;
    protected $table = 'organisasi';
    protected $fillable = [
        'jenis_organisasi',
        'alumni_id',
        'kegiatan',
        'periode_jabatan',
        'status_aktif',
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }
}

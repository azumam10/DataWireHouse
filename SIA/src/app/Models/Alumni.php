<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alumni extends Model
{
    use HasFactory;

    protected $table = 'alumnis';
    protected $fillable = [
        'nama_lengkap', 'nim', 'email', 'no_hp', 'foto',
        'fakultas_id', 'jurusan_id', 'angkatan', 'pekerjaan', 'status_alumni'
    ];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function distribusiManual()
    {
        return $this->hasMany(LokerDistribusiManual::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';
    protected $fillable = [
        'judul_event', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai',
        'lokasi', 'foto_event', 'target_fakultas_id', 'target_jurusan_id', 'target_angkatan'
    ];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'target_fakultas_id');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'target_jurusan_id');
    }

    public function targetFakultas()
    {
        return $this->belongsTo(Fakultas::class, 'target_fakultas_id');
    }

    public function targetJurusan()
    {
        return $this->belongsTo(Jurusan::class, 'target_jurusan_id');
    }

}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusan';
    protected $fillable = ['fakultas_id', 'nama_jurusan'];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function alumnis()
    {
        return $this->hasMany(Alumni::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'target_jurusan_id');
    }

    public function getNamaDenganIdAttribute(): string
    {
        return "{$this->nama_jurusan} - ID Jurusan: {$this->id}";
    }
}

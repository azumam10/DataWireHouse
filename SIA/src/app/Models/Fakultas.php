<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fakultas extends Model
{
    use HasFactory;

    protected $table = 'fakultas';
    protected $fillable = ['nama_fakultas'];

    public function jurusan()
    {
        return $this->hasMany(Jurusan::class);
    }

    public function alumnis()
    {
        return $this->hasMany(Alumni::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'target_fakultas_id');
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }
}

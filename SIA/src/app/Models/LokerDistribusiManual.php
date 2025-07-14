<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LokerDistribusiManual extends Model
{
    use HasFactory;

    protected $table = 'loker_distribusi_manual';
    protected $fillable = [
        'alumni_id', 'loker_id', 'status_kirim', 'waktu_kirim'
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }

    public function loker()
    {
        return $this->belongsTo(Loker::class);
    }
}

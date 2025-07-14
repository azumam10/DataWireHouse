<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniTidakBekerja extends Alumni
{
    protected $table = 'alumnis';

    // Filter default untuk alumni yang belum bekerja
    protected static function booted()
    {
        static::addGlobalScope('belum_bekerja', function ($builder) {
            $builder->whereNull('pekerjaan');
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniBekerja extends Alumni
{
    protected $table = 'alumnis';

    // Filter default untuk hanya alumni yang bekerja
    protected static function booted()
    {
        static::addGlobalScope('bekerja', function ($builder) {
            $builder->whereNotNull('pekerjaan');
        });
    }
}

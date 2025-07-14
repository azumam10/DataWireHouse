<?php

namespace App\Filament\Admin\Resources\AlumniTidakBekerjaResource\Pages;

use App\Filament\Admin\Resources\AlumniTidakBekerjaResource;
use Filament\Resources\Pages\ListRecords;

class ListAlumniTidakBekerjas extends ListRecords
{
    protected static string $resource = AlumniTidakBekerjaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

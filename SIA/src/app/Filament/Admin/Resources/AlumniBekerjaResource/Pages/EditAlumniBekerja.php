<?php

namespace App\Filament\Admin\Resources\AlumniBekerjaResource\Pages;

use App\Filament\Admin\Resources\AlumniBekerjaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlumniBekerja extends EditRecord
{
    protected static string $resource = AlumniBekerjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

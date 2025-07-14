<?php

namespace App\Filament\Admin\Resources\AlumniResource\Pages;

use App\Filament\Admin\Resources\AlumniResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlumni extends EditRecord
{
    protected static string $resource = AlumniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

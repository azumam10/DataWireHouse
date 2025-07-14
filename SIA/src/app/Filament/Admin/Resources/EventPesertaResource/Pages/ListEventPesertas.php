<?php

namespace App\Filament\Admin\Resources\EventPesertaResource\Pages;

use App\Filament\Admin\Resources\EventPesertaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventPesertas extends ListRecords
{
    protected static string $resource = EventPesertaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

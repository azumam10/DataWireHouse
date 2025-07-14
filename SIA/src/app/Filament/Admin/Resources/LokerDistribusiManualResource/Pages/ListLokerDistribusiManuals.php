<?php

namespace App\Filament\Admin\Resources\LokerDistribusiManualResource\Pages;

use App\Filament\Admin\Resources\LokerDistribusiManualResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLokerDistribusiManuals extends ListRecords
{
    protected static string $resource = LokerDistribusiManualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

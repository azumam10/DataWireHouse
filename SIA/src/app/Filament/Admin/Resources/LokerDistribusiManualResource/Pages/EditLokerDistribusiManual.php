<?php

namespace App\Filament\Admin\Resources\LokerDistribusiManualResource\Pages;

use App\Filament\Admin\Resources\LokerDistribusiManualResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLokerDistribusiManual extends EditRecord
{
    protected static string $resource = LokerDistribusiManualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

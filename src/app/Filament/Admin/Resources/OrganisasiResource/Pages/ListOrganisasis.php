<?php

namespace App\Filament\Admin\Resources\OrganisasiResource\Pages;

use App\Filament\Admin\Resources\OrganisasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrganisasis extends ListRecords
{
    protected static string $resource = OrganisasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

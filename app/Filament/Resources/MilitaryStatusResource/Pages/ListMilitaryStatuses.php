<?php

namespace App\Filament\Resources\MilitaryStatusResource\Pages;

use App\Filament\Resources\MilitaryStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMilitaryStatuses extends ListRecords
{
    protected static string $resource = MilitaryStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

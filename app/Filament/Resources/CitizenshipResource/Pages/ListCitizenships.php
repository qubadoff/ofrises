<?php

namespace App\Filament\Resources\CitizenshipResource\Pages;

use App\Filament\Resources\CitizenshipResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\ListRecords;

class ListCitizenships extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    protected static string $resource = CitizenshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            LocaleSwitcher::make()
        ];
    }
}

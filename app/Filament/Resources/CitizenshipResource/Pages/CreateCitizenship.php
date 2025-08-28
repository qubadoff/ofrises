<?php

namespace App\Filament\Resources\CitizenshipResource\Pages;

use App\Filament\Resources\CitizenshipResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\LocaleSwitcher;

class CreateCitizenship extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = CitizenshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make()
        ];
    }
}

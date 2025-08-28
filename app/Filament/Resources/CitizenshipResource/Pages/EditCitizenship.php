<?php

namespace App\Filament\Resources\CitizenshipResource\Pages;

use App\Filament\Resources\CitizenshipResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\EditRecord;

class EditCitizenship extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = CitizenshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            LocaleSwitcher::make()
        ];
    }
}

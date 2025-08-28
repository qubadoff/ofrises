<?php

namespace App\Filament\Resources\EducationTypeResource\Pages;

use App\Filament\Resources\EducationTypeResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\EditRecord;

class EditEducationType extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = EducationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            LocaleSwitcher::make()
        ];
    }
}

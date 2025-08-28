<?php

namespace App\Filament\Resources\EducationTypeResource\Pages;

use App\Filament\Resources\EducationTypeResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;

class CreateEducationType extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = EducationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make()
        ];
    }
}

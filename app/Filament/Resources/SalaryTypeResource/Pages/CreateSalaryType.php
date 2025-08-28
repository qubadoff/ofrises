<?php

namespace App\Filament\Resources\SalaryTypeResource\Pages;

use App\Filament\Resources\SalaryTypeResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;

class CreateSalaryType extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = SalaryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make()
        ];
    }
}

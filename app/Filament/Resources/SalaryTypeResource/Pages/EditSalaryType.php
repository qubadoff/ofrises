<?php

namespace App\Filament\Resources\SalaryTypeResource\Pages;

use App\Filament\Resources\SalaryTypeResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\EditRecord;

class EditSalaryType extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = SalaryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            LocaleSwitcher::make()
        ];
    }
}

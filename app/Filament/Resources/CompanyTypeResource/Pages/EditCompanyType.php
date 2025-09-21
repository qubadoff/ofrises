<?php

namespace App\Filament\Resources\CompanyTypeResource\Pages;

use App\Filament\Resources\CompanyTypeResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\EditRecord;

class EditCompanyType extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = CompanyTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            LocaleSwitcher::make()
        ];
    }
}

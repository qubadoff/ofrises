<?php

namespace App\Filament\Resources\CompanyTypeResource\Pages;

use App\Filament\Resources\CompanyTypeResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\ListRecords;

class ListCompanyTypes extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = CompanyTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            LocaleSwitcher::make()
        ];
    }
}

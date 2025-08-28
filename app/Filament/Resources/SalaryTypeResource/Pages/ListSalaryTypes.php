<?php

namespace App\Filament\Resources\SalaryTypeResource\Pages;

use App\Filament\Resources\SalaryTypeResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\ListRecords;

class ListSalaryTypes extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = SalaryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            LocaleSwitcher::make()
        ];
    }
}

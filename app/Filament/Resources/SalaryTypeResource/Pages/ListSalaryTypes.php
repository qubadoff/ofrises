<?php

namespace App\Filament\Resources\SalaryTypeResource\Pages;

use App\Filament\Resources\SalaryTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalaryTypes extends ListRecords
{
    protected static string $resource = SalaryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

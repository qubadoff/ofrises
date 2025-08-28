<?php

namespace App\Filament\Resources\WorkExpectationResource\Pages;

use App\Filament\Resources\WorkExpectationResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\ListRecords;

class ListWorkExpectations extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = WorkExpectationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            LocaleSwitcher::make()
        ];
    }
}

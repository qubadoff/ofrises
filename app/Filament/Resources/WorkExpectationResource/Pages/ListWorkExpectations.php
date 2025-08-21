<?php

namespace App\Filament\Resources\WorkExpectationResource\Pages;

use App\Filament\Resources\WorkExpectationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorkExpectations extends ListRecords
{
    protected static string $resource = WorkExpectationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

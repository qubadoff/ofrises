<?php

namespace App\Filament\Resources\EducationTypeResource\Pages;

use App\Filament\Resources\EducationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEducationTypes extends ListRecords
{
    protected static string $resource = EducationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

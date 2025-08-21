<?php

namespace App\Filament\Resources\EducationTypeResource\Pages;

use App\Filament\Resources\EducationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEducationType extends EditRecord
{
    protected static string $resource = EducationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

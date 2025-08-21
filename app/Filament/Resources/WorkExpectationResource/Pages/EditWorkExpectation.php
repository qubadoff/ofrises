<?php

namespace App\Filament\Resources\WorkExpectationResource\Pages;

use App\Filament\Resources\WorkExpectationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkExpectation extends EditRecord
{
    protected static string $resource = WorkExpectationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

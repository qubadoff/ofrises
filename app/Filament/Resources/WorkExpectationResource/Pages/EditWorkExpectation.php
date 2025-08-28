<?php

namespace App\Filament\Resources\WorkExpectationResource\Pages;

use App\Filament\Resources\WorkExpectationResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\EditRecord;

class EditWorkExpectation extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = WorkExpectationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            LocaleSwitcher::make()
        ];
    }
}

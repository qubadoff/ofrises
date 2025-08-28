<?php

namespace App\Filament\Resources\WorkExpectationResource\Pages;

use App\Filament\Resources\WorkExpectationResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkExpectation extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = WorkExpectationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make()
        ];
    }

}

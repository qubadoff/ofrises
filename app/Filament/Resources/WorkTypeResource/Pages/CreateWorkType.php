<?php

namespace App\Filament\Resources\WorkTypeResource\Pages;

use App\Filament\Resources\WorkTypeResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkType extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = WorkTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make()
        ];
    }
}

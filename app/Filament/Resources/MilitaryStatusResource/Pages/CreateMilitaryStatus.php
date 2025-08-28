<?php

namespace App\Filament\Resources\MilitaryStatusResource\Pages;

use App\Filament\Resources\MilitaryStatusResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;

class CreateMilitaryStatus extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = MilitaryStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make()
        ];
    }
}

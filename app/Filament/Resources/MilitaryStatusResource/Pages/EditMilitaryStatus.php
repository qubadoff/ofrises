<?php

namespace App\Filament\Resources\MilitaryStatusResource\Pages;

use App\Filament\Resources\MilitaryStatusResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\EditRecord;

class EditMilitaryStatus extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = MilitaryStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            LocaleSwitcher::make()
        ];
    }
}

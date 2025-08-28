<?php

namespace App\Filament\Resources\CurrencyResource\Pages;

use App\Filament\Resources\CurrencyResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\EditRecord;

class EditCurrency extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = CurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            LocaleSwitcher::make()
        ];
    }
}

<?php

namespace App\Filament\Resources\LanguageLevelResource\Pages;

use App\Filament\Resources\LanguageLevelResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\ListRecords;

class ListLanguageLevels extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = LanguageLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            LocaleSwitcher::make()
        ];
    }
}

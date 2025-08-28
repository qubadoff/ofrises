<?php

namespace App\Filament\Resources\LanguageLevelResource\Pages;

use App\Filament\Resources\LanguageLevelResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;

class CreateLanguageLevel extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = LanguageLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make()
        ];
    }
}

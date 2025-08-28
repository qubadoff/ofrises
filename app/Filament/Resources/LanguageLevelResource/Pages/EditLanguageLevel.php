<?php

namespace App\Filament\Resources\LanguageLevelResource\Pages;

use App\Filament\Resources\LanguageLevelResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\EditRecord;

class EditLanguageLevel extends EditRecord
{

    use EditRecord\Concerns\Translatable;
    protected static string $resource = LanguageLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            LocaleSwitcher::make()
        ];
    }
}

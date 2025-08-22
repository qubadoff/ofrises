<?php

namespace App\Filament\Resources\LanguageLevelResource\Pages;

use App\Filament\Resources\LanguageLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLanguageLevel extends EditRecord
{
    protected static string $resource = LanguageLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

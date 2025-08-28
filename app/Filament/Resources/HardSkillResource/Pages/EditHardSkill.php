<?php

namespace App\Filament\Resources\HardSkillResource\Pages;

use App\Filament\Resources\HardSkillResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\EditRecord;

class EditHardSkill extends EditRecord
{

    use EditRecord\Concerns\Translatable;
    protected static string $resource = HardSkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            LocaleSwitcher::make()
        ];
    }
}

<?php

namespace App\Filament\Resources\HardSkillResource\Pages;

use App\Filament\Resources\HardSkillResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHardSkill extends EditRecord
{
    protected static string $resource = HardSkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

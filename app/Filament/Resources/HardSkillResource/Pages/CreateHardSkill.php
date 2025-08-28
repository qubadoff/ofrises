<?php

namespace App\Filament\Resources\HardSkillResource\Pages;

use App\Filament\Resources\HardSkillResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;

class CreateHardSkill extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = HardSkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make()
        ];
    }
}

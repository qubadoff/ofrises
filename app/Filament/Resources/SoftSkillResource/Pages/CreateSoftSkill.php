<?php

namespace App\Filament\Resources\SoftSkillResource\Pages;

use App\Filament\Resources\SoftSkillResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;

class CreateSoftSkill extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = SoftSkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make()
        ];
    }
}

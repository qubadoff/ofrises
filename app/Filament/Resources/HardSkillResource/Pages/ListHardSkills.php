<?php

namespace App\Filament\Resources\HardSkillResource\Pages;

use App\Filament\Resources\HardSkillResource;
use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\ListRecords;

class ListHardSkills extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = HardSkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            LocaleSwitcher::make()
        ];
    }
}

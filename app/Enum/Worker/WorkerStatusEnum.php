<?php

namespace App\Enum\Worker;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum WorkerStatusEnum: int implements HasLabel, HasColor
{
    case ACTIVE = 1;
    case INACTIVE = 2;

    case PENDING = 3;

    case DEACTIVATED = 4;

    case DELETED = 5;

    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::PENDING => 'Pending',
            self::DEACTIVATED => 'Deactivated',
            self::DELETED => 'Deleted',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ACTIVE => 'primary',
            self::INACTIVE, self::DELETED, self::DEACTIVATED => 'danger',
            self::PENDING => 'warning',
        };
    }
}

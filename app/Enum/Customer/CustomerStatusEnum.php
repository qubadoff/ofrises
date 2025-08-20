<?php

namespace App\Enum\Customer;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CustomerStatusEnum: int implements HasLabel, HasColor
{
    case ACTIVE = 1;

    case INACTIVE = 2;

    case PENDING = 3;

    case DELETED = 4;

    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::PENDING => 'Pending',
            self::DELETED => 'Deleted',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE, self::DELETED => 'danger',
            self::PENDING => 'warning',
        };
    }
}

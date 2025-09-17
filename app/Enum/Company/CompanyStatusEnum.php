<?php

namespace App\Enum\Company;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CompanyStatusEnum: int implements HasLabel, HasColor
{
    case PENDING = 0;

    case ACTIVE = 1;

    case INACTIVE = 2;

    case REJECTED = 3;

    case DELETED = 4;

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::REJECTED => 'Rejected',
            self::DELETED => 'Deleted',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'primary',
            self::ACTIVE => 'success',
            self::INACTIVE, self::DELETED => 'danger',
            self::REJECTED => 'warning',
        };
    }
}

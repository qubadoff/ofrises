<?php

namespace App\Enum\Customer;

enum CustomerStatusEnum: int
{
    case ACTIVE = 1;

    case INACTIVE = 2;

    case PENDING = 3;
}

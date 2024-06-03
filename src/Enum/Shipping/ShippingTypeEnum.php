<?php

declare(strict_types=1);

namespace App\Enum\Shipping;

enum ShippingTypeEnum: string
{
    case PICK_UP = 'pickup';

    case COURIER = 'courier';
}

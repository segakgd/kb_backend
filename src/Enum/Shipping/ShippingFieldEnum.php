<?php

declare(strict_types=1);

namespace App\Enum\Shipping;

// todo -> oleg: Пока нафигачил по-минимуму. Нужно дополнить!
enum ShippingFieldEnum: string
{
    case TEXTAREA = 'textarea';

    case DATETIME = 'datetime';

    case CITY = 'city';
}

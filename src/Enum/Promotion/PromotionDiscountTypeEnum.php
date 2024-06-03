<?php

declare(strict_types=1);

namespace App\Enum\Promotion;

enum PromotionDiscountTypeEnum: string
{
    case PERCENT = 'PERCENT';

    case CURRENCY = 'CURRENCY';

    case SHIPPING = 'SHIPPING';
}

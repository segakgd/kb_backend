<?php

declare(strict_types=1);

namespace App\Enum\Promotion;

enum PromotionTypeEnum: string
{
    case PROMO_CODE = 'PROMO_CODE';

    case DISCOUNT = 'DISCOUNT';
}

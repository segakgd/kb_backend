<?php

declare(strict_types=1);

namespace App\Enum\Shipping;

enum ShippingCalculationTypeEnum: string
{
    case PERCENT = 'percent';

    case CURRENCY = 'currency';

    public static function getValues(): array
    {
        $result = [];

        foreach (self::cases() as $case) {
            $result[] = $case->value;
        }

        return $result;
    }
}

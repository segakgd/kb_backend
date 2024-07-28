<?php

namespace App\Enum;

use BackedEnum;

trait EnumUtilsTrait
{
    public function is(BackedEnum $case): bool
    {
        return $this === $case;
    }

    public function isNot(BackedEnum $case): bool
    {
        return $this !== $case;
    }

    public static function isIn(mixed $value): bool
    {
        /** @var BackedEnum $backedEnum */
        foreach (static::cases() as $backedEnum) {
            if ($backedEnum->value === $value) {
                return true;
            }
        }

        return false;
    }

    public static function isNotIn(mixed $value): bool
    {
        return !static::isIn($value);
    }
}

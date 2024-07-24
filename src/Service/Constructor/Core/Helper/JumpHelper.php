<?php

namespace App\Service\Constructor\Core\Helper;

use App\Enum\NavigateEnum;
use App\Enum\TargetEnum;

class JumpHelper
{
    public static function getJumpFromNavigate(string $content): ?TargetEnum
    {
        return match ($content) {
            NavigateEnum::ToMain->value             => TargetEnum::Main,
            NavigateEnum::ToMainLong->value         => TargetEnum::Main,
            NavigateEnum::PlaceAnOrder->value       => TargetEnum::PlaceAnOrder,
            NavigateEnum::ToProducts->value         => TargetEnum::ProductCategoryChain,
            NavigateEnum::ToCategoryProducts->value => TargetEnum::StartChain,
            default                                 => null,
        };
    }
}

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
            NavigateEnum::ToCart->value             => TargetEnum::Cart,
            NavigateEnum::ToProducts->value         => TargetEnum::ProductCategoryChain,
            NavigateEnum::ToCategoryProducts->value => TargetEnum::StartChain,
            default                                 => null,
        };
    }
}

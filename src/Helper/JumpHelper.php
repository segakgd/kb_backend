<?php

namespace App\Helper;

use App\Enum\JumpEnum;
use App\Enum\NavigateEnum;

class JumpHelper
{
    public static function getJumpFromNavigate(string $content): ?JumpEnum
    {
        return match ($content) {
            NavigateEnum::ToMain->value => JumpEnum::Main,
            NavigateEnum::ToCart->value => JumpEnum::Cart,
            NavigateEnum::ToProducts->value => JumpEnum::ShopProducts,
            NavigateEnum::ToCategoryProducts->value => JumpEnum::ShowShopProductsCategory,
            default => null,
        };
    }
}

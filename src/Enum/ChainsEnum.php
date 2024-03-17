<?php

namespace App\Enum;

enum ChainsEnum: string
{
    case ShowShopProductsCategory = 'show.shop.products.category';

    case ShopProductsCategory = 'shop.products.category';

    case ShopProducts = 'shop.products';

    case ShopVariant = 'shop.variant';

    case ShopVariantCount = 'shop.variant.count';
    case ShopFinal = 'shop.final';
}

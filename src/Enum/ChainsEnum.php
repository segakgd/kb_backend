<?php

namespace App\Enum;

enum ChainsEnum: string
{
    // Категории
    case ShowShopProductsCategory = 'show.shop.products.category';

    case ShopProductsCategory = 'shop.products.category';

    case ShopProducts = 'shop.products';


    // Популярные
    case ShopProductsPopular = 'shop.products.popular';

    case ShopProductPopular = 'shop.product.popular';

    // Акционные
    case ShopProductsPromo = 'shop.products.promo';

    case ShopProductPromo = 'shop.product.promo';

    // Общее
    case ShopVariant = 'shop.variant';

    case ShopVariantCount = 'shop.variant.count';

    case ShopFinal = 'shop.final';


    // Овормление заказа
    case CartContact = 'cart.contact';

    case CartShipping = 'cart.shipping';
}

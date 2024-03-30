<?php

namespace App\Enum;

enum GotoChainsEnum: string
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
    case CartViewContact = 'cart.view.contact';

    case CartContact = 'cart.contact';

    case CartPhoneContact = 'cart.phone.contact';

    case CartShipping = 'cart.shipping';

    case CartShippingCountry = 'cart.shipping.country';
    case CartShippingRegion = 'cart.shipping.region';
    case CartShippingCity = 'cart.shipping.city';
    case CartShippingStreet = 'cart.shipping.street';
    case CartShippingNumberHome = 'cart.shipping.numberHome';
    case CartShippingEntrance = 'cart.shipping.entrance';
    case CartShippingApartment = 'cart.shipping.apartment';
    case CartSave = 'cart.save';
    case CartFinish = 'cart.finish';



    // test
    case refChain1 = 'ref.chain.1';

    case refChain2 = 'ref.chain.2';

    case refChain3 = 'ref.chain.3';

    case refChain4 = 'ref.chain.4';

    case refChain5 = 'ref.chain.5';

    case refChain6 = 'ref.chain.6';

    case refChain7 = 'ref.chain.7';

    case refChain8 = 'ref.chain.8';

    case refChain9 = 'ref.chain.9';

    case refChain10 = 'ref.chain.10';
}

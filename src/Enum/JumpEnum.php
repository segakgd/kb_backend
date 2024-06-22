<?php

namespace App\Enum;

enum JumpEnum: string
{
    //    // Категории
    //    case ShowShopProductsCategory = 'show.shop.products.category';
    //
    //    case ShopProductsCategory = 'shop.products.category';
    //
    //    case ShopProducts = 'shop.products';
    //
    //    case ShopProduct = 'shop.product';
    //
    //
    //    // Популярные
    //    case ShopProductsPopular = 'shop.products.popular';
    //
    //    case ShopProductPopular = 'shop.product.popular';
    //
    //    // Акционные
    //    case ShopProductsPromo = 'shop.products.promo';
    //
    //    case ShopProductPromo = 'shop.product.promo';
    //
    //    // Общее
    //    case ShopVariant = 'shop.variant';
    //
    //    case ShopVariantCount = 'shop.variant.count';
    //
    //    case ShopFinal = 'shop.final';
    //
    //
    //    // Овормление заказа
    //    case CartViewContact = 'cart.view.contact';
    //
    //    case CartContact = 'cart.contact';
    //
    //    case CartPhoneContact = 'cart.phone.contact';
    //
    //    case CartShipping = 'cart.shipping';
    //
    //    case CartShippingCountry = 'cart.shipping.country';
    //    case CartShippingRegion = 'cart.shipping.region';
    //    case CartShippingCity = 'cart.shipping.city';
    //    case CartShippingStreet = 'cart.shipping.street';
    //    case CartShippingNumberHome = 'cart.shipping.numberHome';
    //    case CartShippingEntrance = 'cart.shipping.entrance';
    //    case CartShippingApartment = 'cart.shipping.apartment';
    //    case CartSave = 'cart.save';
    //    case CartFinish = 'cart.finish';

    case Main = 'main';
    case Cart = 'cart';

    // new
    case GreetingChain = 'greeting.chain';
    case StartChain = 'start.chain';
    case ProductCategoryChain = 'product.category.chain';
    case ProductsByCategoryChain = 'products.by.category.chain';
    case VariantsProductChain = 'variants.product.chain';
    case VariantProductChain = 'variant.product.chain';
    case FinishChain = 'finish.chain';
}

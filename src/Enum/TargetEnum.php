<?php

namespace App\Enum;

enum TargetEnum: string
{
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

    // Отображаем что в корзине
    case OrderGreetingChain = 'order.greeting.chain';

    // Запись контактов
    case OrderContactsFullNameChain = 'order.contacts.full-name.chain';

    // Запись контактов
    case OrderContactsPhoneChain = 'order.contacts.phone.chain';

    // Нужна ли доставка (если да, то заполняем, если нет то прыгаем)
    case OrderShippingSwitch = 'order.shipping.switch';

    // Заполняем доставку (страна, регион, район, город, улица, дом, квартира)
    case OrderShippingChain = 'order.shipping.chain';

    case OrderFinishChain = 'order.finish.chain';
}

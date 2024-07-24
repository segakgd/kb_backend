<?php

namespace App\Enum;

enum NavigateEnum: string
{
    case ToMain = 'В главное меню';
    case PlaceAnOrder = 'Оформить заказ';
    case ToMainLong = 'Вернуться в главное меню';
    case ToCart = 'Моя корзина';
    case ToProducts = 'К товарам';
    case ToCategoryProducts = 'К категориям';
}

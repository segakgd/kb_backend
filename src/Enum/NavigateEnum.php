<?php

namespace App\Enum;

enum NavigateEnum: string
{
    case ToMain = 'В главное меню';
    case ToMainLong = 'Вернуться в главное меню';
    case ToCart = 'Моя корзина';
    case ToProducts = 'К товарам';
    case ToCategoryProducts = 'К категориям';
}

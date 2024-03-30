<?php

namespace App\Enum;

enum NavigateEnum: string
{
    case ToMain = 'Вернуться в главное меню';
    case ToCart = 'Моя корзина';
    case ToProducts = 'вернуться к товарам';
    case ToCategoryProducts = 'вернуться к категориям';
}

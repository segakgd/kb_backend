<?php

namespace App\Enum;

enum TargetAliasEnum: string
{
    case Main = 'main';
    case Cart = 'cart';
    case PlaceAnOrder = 'place_an_order';
    case Default = 'default';
}

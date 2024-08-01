<?php

namespace App\Enum;

enum TargetEnum: string
{
    case Main = TargetAliasEnum::Main->value;
    case Cart = TargetAliasEnum::Cart->value;

    case PlaceAnOrder = TargetAliasEnum::PlaceAnOrder->value;
}

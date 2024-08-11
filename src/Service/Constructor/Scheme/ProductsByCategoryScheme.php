<?php

namespace App\Service\Constructor\Scheme;

use App\Service\Constructor\Actions\Order\OrderContactsFullNameAction;
use App\Service\Constructor\Actions\Order\OrderContactsPhoneAction;
use App\Service\Constructor\Actions\Order\OrderFinishAction;
use App\Service\Constructor\Actions\Order\OrderGreetingAction;
use App\Service\Constructor\Actions\Order\OrderShippingAction;
use App\Service\Constructor\Actions\Order\OrderShippingSwitchAction;

class ProductsByCategoryScheme
{
    public static function getSchemaName(): string
    {
        return 'products_by_category';
    }

    public static function scheme(): array
    {
        return [
            OrderGreetingAction::getName(),
            OrderContactsFullNameAction::getName(),
            OrderContactsPhoneAction::getName(),
            OrderShippingSwitchAction::getName(),
            OrderShippingAction::getName(),
            OrderFinishAction::getName(),
        ];
    }
}

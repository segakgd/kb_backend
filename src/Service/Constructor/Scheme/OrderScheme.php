<?php

namespace App\Service\Constructor\Scheme;

use App\Service\Constructor\Actions\Order\OrderContactsFullNameAction;
use App\Service\Constructor\Actions\Order\OrderContactsPhoneAction;
use App\Service\Constructor\Actions\Order\OrderFinishAction;
use App\Service\Constructor\Actions\Order\OrderGreetingAction;
use App\Service\Constructor\Actions\Order\OrderShippingAction;
use App\Service\Constructor\Actions\Order\OrderShippingSwitchAction;

class OrderScheme
{
    public static function getSchemaName(): string
    {
        return 'order';
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

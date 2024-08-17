<?php

namespace App\Controller\Admin\Lead\Response\Order\Shipping;

use App\Controller\AbstractResponse;

class ShippingRespDto extends AbstractResponse
{
    public const TYPE_COURIER = 'courier';

    public const TYPE_PICKUP = 'pickup';

    public string $name;

    /** courier - курьер, pickup - самовывоз */
    public string $type = 'pickup';

    public int $totalAmount = 0;

    public string $totalAmountWF = '0.0';

}

<?php

namespace App\Service\Admin\Ecommerce\Deal;

use App\Dto\deprecated\Ecommerce\OrderDto;
use App\Entity\Lead\DealOrder;

interface OrderManagerInterface
{
    public function add(OrderDto $dto): DealOrder;

    public function update(OrderDto $dto): ?DealOrder;
}

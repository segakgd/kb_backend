<?php

namespace App\Controller\Admin\Shipping\Response;

use App\Controller\Admin\Shipping\DTO\Response\ShippingRespDto;
use App\Entity\Ecommerce\Shipping;

class ShippingViewOneResponse
{
    public function makeResponse(Shipping $shipping): ShippingRespDto
    {
        return (new ShippingRespDto())
            ->setName($shipping->getTitle())
            ->setType($shipping->getType())
            ->setApplyFromAmount($shipping->getApplyFromAmount())
            ->setIsActive($shipping->isActive())
            ->setApplyToAmount($shipping->getApplyToAmount())
            ->setCalculationType($shipping->getCalculationType())
            ->setDescription($shipping->getDescription())
            ->setPrice($shipping->getPrice())
            ->setFields($shipping->getFields());
    }
}

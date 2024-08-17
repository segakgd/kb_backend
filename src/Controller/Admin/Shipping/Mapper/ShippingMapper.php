<?php

declare(strict_types=1);

namespace App\Controller\Admin\Shipping\Mapper;

use App\Controller\Admin\Shipping\Request\ShippingRequest;
use App\Entity\Ecommerce\Shipping;

class ShippingMapper
{
    public static function mapRequestToEntity(ShippingRequest $shippingReqDto): Shipping
    {
        return (new Shipping())
            ->setTitle($shippingReqDto->getTitle())
            ->setDescription($shippingReqDto->getDescription())
            ->setType($shippingReqDto->getType())
            ->setCalculationType($shippingReqDto->getCalculationType())
            ->setActive($shippingReqDto->isActive())
            ->setApplyFromAmount($shippingReqDto->getApplyFromAmount())
            ->setApplyToAmount($shippingReqDto->getApplyToAmount())
            ->setFreeFrom($shippingReqDto->getFreeFrom())
            ->setIsNotFixed($shippingReqDto->isNotFixed())
            ->setPrice($shippingReqDto->getPrice());
    }

    public static function mapRequestToExistingEntity(ShippingRequest $shippingReqDto, Shipping $shipping): Shipping
    {
        return $shipping
            ->setTitle($shippingReqDto->getTitle())
            ->setDescription($shippingReqDto->getDescription())
            ->setType($shippingReqDto->getType())
            ->setCalculationType($shippingReqDto->getCalculationType())
            ->setActive($shippingReqDto->isActive())
            ->setApplyFromAmount($shippingReqDto->getApplyFromAmount())
            ->setApplyToAmount($shippingReqDto->getApplyToAmount())
            ->setFreeFrom($shippingReqDto->getFreeFrom())
            ->setIsNotFixed($shippingReqDto->isNotFixed())
            ->setPrice($shippingReqDto->getPrice());
    }
}

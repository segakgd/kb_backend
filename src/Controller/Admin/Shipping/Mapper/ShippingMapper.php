<?php

declare(strict_types=1);

namespace App\Controller\Admin\Shipping\Mapper;

use App\Controller\Admin\Shipping\DTO\Request\ShippingReqDto;
use App\Entity\Ecommerce\Shipping;

class ShippingMapper
{
    public static function mapRequestToEntity(ShippingReqDto $shippingReqDto): Shipping
    {
        return (new Shipping())
            ->setTitle($shippingReqDto->getTitle())
            ->setDescription($shippingReqDto->getDescription())
            ->setType($shippingReqDto->getType())
            ->setCalculationType($shippingReqDto->getCalculationType())
            ->setIsActive($shippingReqDto->isActive())
            ->setApplyFromAmount($shippingReqDto->getApplyFromAmount())
            ->setApplyToAmount($shippingReqDto->getApplyToAmount())
            ->setFreeFrom($shippingReqDto->getFreeFrom())
            ->setIsNotFixed($shippingReqDto->isNotFixed())
            ->setPrice($shippingReqDto->getPrice());
    }

    public static function mapRequestToExistingEntity(ShippingReqDto $shippingReqDto, Shipping $shipping): Shipping
    {
        return $shipping
            ->setTitle($shippingReqDto->getTitle())
            ->setDescription($shippingReqDto->getDescription())
            ->setType($shippingReqDto->getType())
            ->setCalculationType($shippingReqDto->getCalculationType())
            ->setIsActive($shippingReqDto->isActive())
            ->setApplyFromAmount($shippingReqDto->getApplyFromAmount())
            ->setApplyToAmount($shippingReqDto->getApplyToAmount())
            ->setFreeFrom($shippingReqDto->getFreeFrom())
            ->setIsNotFixed($shippingReqDto->isNotFixed())
            ->setPrice($shippingReqDto->getPrice());
    }
}

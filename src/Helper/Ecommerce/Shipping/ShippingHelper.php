<?php

declare(strict_types=1);

namespace App\Helper\Ecommerce\Shipping;

use App\Controller\Admin\Shipping\DTO\Request\ShippingReqDto;
use App\Controller\Admin\Shipping\DTO\Response\ShippingRespDto;
use App\Entity\Ecommerce\Shipping;

class ShippingHelper
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

    public static function MapToResponseDto(Shipping $shipping): ShippingRespDto
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

    public static function mapArrayToResponseDto(array $shippingCollection): array
    {
        return array_map(function (Shipping $shipping) {
            return self::MapToResponseDto($shipping);
        }, $shippingCollection);
    }
}

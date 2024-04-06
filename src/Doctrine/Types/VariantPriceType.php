<?php

declare(strict_types=1);

namespace App\Doctrine\Types;

use App\Dto\Product\Variants\VariantPriceDto;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

class VariantPriceType extends JsonType
{
    public const VARIANT_PRICE_TYPE = 'variant_price_type';

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $values = parent::convertToPHPValue($value, $platform);
        return array_map(function ($value) {
            return VariantPriceDto::fromArray($value);
        }, $values);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $values = array_map(function (VariantPriceDto $variantPriceDto) {
            return $variantPriceDto->toArray();
        }, $value);
        return parent::convertToDatabaseValue($values, $platform);
    }

    public function getName(): string
    {
        return self::VARIANT_PRICE_TYPE;
    }
}

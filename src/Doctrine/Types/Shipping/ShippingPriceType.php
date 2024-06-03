<?php

declare(strict_types=1);

namespace App\Doctrine\Types\Shipping;

use App\Dto\Ecommerce\Shipping\ShippingPriceDto;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use InvalidArgumentException;

class ShippingPriceType extends JsonType
{
    public const SHIPPING_PRICE_TYPE = 'shipping_price_type';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ShippingPriceDto
    {
        if (empty($value)) {  // Checks for both null and empty string
            return null;
        }

        $data = json_decode($value, true);
        if ($data === null) {
            return null;
        }

        return ShippingPriceDto::fromArray($data);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;  // Return null directly if the value is null
        }

        if ($value instanceof ShippingPriceDto) {
            return json_encode($value->toArray());
        }

        throw new InvalidArgumentException('Value must be an instance of ShippingPriceDto or null');
    }

    public function getName(): string
    {
        return self::SHIPPING_PRICE_TYPE;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}

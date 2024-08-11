<?php

namespace App\Doctrine\Types;

use App\Service\Constructor\Core\Dto\Responsible;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

class ResponsibleType extends JsonType
{
    public const RESPONSIBLE_TYPE = 'responsible_type';

    public function convertToPHPValue($value, AbstractPlatform $platform): array
    {
        $values = parent::convertToPHPValue($value, $platform);

        return array_map(function ($value) {
            return Responsible::fromArray($value);
        }, $values);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        $values = array_map(function (Responsible $variantPriceDto) {
            return $variantPriceDto->toArray();
        }, $value);

        return parent::convertToDatabaseValue($values, $platform);
    }

    public function getName(): string
    {
        return self::RESPONSIBLE_TYPE;
    }
}

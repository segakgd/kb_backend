<?php

namespace App\Doctrine\Types;

use App\Service\Constructor\Core\Dto\Responsible;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

class ResponsibleType extends JsonType
{
    public const RESPONSIBLE_TYPE = 'responsible_type';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Responsible
    {
        if (is_null($value)) {
            return null;
        }

        $arrayValue = parent::convertToPHPValue($value, $platform);

        return Responsible::fromArray($arrayValue);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (is_null($value)) {
            return null;
        }

        /** @var Responsible $value */
        $arrayValue = $value->toArray();

        return parent::convertToDatabaseValue($arrayValue, $platform);
    }

    public function getName(): string
    {
        return self::RESPONSIBLE_TYPE;
    }
}

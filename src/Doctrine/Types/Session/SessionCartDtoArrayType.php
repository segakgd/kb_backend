<?php

namespace App\Doctrine\Types\Session;

use App\Dto\SessionCache\Cache\CacheCartDto;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

class SessionCartDtoArrayType extends JsonType
{
    public const TYPE_NAME = 'session_cart_dto_array';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CacheCartDto
    {
        if ($value === null) {
            return null;
        }

        $decodedValue = parent::convertToPHPValue($value, $platform);

        return CacheCartDto::fromArray($decodedValue);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (is_null($value)) {
            return null;
        }

        /** @var CacheCartDto $value */
        return parent::convertToDatabaseValue($value->toArray(), $platform);
    }

    public function getName(): string
    {
        return self::TYPE_NAME;
    }
}

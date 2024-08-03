<?php

namespace App\Doctrine\Types\Session;

use App\Dto\SessionCache\Cache\CacheEventDto;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

class SessionEventDtoArrayType extends JsonType
{
    public const TYPE_NAME = 'session_event_dto_array';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CacheEventDto
    {
        if ($value === null) {
            return null;
        }

        $decodedValue = parent::convertToPHPValue($value, $platform);

        return CacheEventDto::fromArray($decodedValue);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (is_null($value)) {
            return null;
        }

        /** @var CacheEventDto $value */
        return parent::convertToDatabaseValue($value->toArray(), $platform);
    }

    public function getName(): string
    {
        return self::TYPE_NAME;
    }
}

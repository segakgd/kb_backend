<?php

namespace App\Doctrine;

use App\Dto\SessionCache\Cache\CacheDto;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

class VisitorSessionCacheDtoArrayType extends JsonType
{
    public const TYPE_NAME = 'visitor_session_cache_dto_array';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CacheDto
    {
        if ($value === null) {
            return null;
        }

        $decodedValue = parent::convertToPHPValue($value, $platform);

        return CacheDto::fromArray($decodedValue);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        /** @var CacheDto $value */
        return parent::convertToDatabaseValue($value->toArray(), $platform);
    }

    public function getName(): string
    {
        return self::TYPE_NAME;
    }
}

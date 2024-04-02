<?php

namespace App\Doctrine;

use App\Dto\Scenario\ScenarioStepDto;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

class ScenarioStepDtoArrayType extends JsonType
{
    public const TYPE_NAME = 'scenario_step_dto_array';

    public function convertToPHPValue($value, AbstractPlatform $platform): array
    {
        if ($value === null) {
            return [];
        }

        $decodedValue = parent::convertToPHPValue($value, $platform);

        $result = [];
        foreach ($decodedValue as $item) {
            $result[] = ScenarioStepDto::fromArray($item);
        }

        return $result;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        $serializedValue = [];
        /** @var ScenarioStepDto $item */
        foreach ($value as $item) {
            $serializedValue[] = $item->toArray();
        }

        return parent::convertToDatabaseValue($serializedValue, $platform);
    }

    public function getName(): string
    {
        return self::TYPE_NAME;
    }
}

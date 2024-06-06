<?php

namespace App\Doctrine\Types;

use App\Dto\Scenario\ScenarioContractDto;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

class ScenarioContractDtoArrayType extends JsonType
{
    public const TYPE_NAME = 'scenario_contract_dto_array';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ScenarioContractDto
    {
        if ($value === null) {
            return null;
        }

        $decodedValue = parent::convertToPHPValue($value, $platform);

        return ScenarioContractDto::fromArray($decodedValue);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        $serializedValue = [];

        /** @var ScenarioContractDto $item */
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

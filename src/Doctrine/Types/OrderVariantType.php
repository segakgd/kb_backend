<?php

declare(strict_types=1);

namespace App\Doctrine\Types;

use App\Controller\Admin\Lead\DTO\Request\Order\Product\OrderVariantReqDto;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

class OrderVariantType extends Type
{
    public const NAME = 'order_variant'; // Changed name to reflect it's an array now

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($column); // JSON is a suitable type for storing arrays
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): array
    {
        $dataArray = json_decode($value, true);
        if (!is_array($dataArray)) {
            return [];
        }

        $dtos = [];
        foreach ($dataArray as $dataItem) {
            $dto = new OrderVariantReqDto();
            $dto->setId($dataItem['id'] ?? 0);
            $dto->setCount($dataItem['count'] ?? null);
            $dto->setPrice($dataItem['price'] ?? 0);
            $dtos[] = $dto;
        }

        return $dtos;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (is_array($value)) {
            $dataArray = array_map(function (OrderVariantReqDto $dto) {
                return [
                    'id' => $dto->getId(),
                    'count' => $dto->getCount(),
                    'price' => $dto->getPrice(),
                ];
            }, $value);

            return json_encode($dataArray);
        }

        throw new InvalidArgumentException('Array of OrderVariantReqDto Instances Expected');
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}

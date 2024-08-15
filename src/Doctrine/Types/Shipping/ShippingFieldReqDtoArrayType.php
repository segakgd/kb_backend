<?php

declare(strict_types=1);

namespace App\Doctrine\Types\Shipping;

use App\Controller\Admin\Shipping\Request\ShippingFieldReqDto;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use InvalidArgumentException;

class ShippingFieldReqDtoArrayType extends JsonType
{
    public const SHIPPING_FIELD_REQ_DTO_ARRAY = 'shipping_field_req_dto_array';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): array
    {
        $data = json_decode($value, true);

        if (empty($data)) {
            return [];
        }

        return array_map(function ($item) {
            $dto = new ShippingFieldReqDto();
            $dto->setName($item['name']);
            $dto->setValue($item['value']);
            $dto->setType($item['type']);

            return $dto;
        }, $data);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): null|false|string
    {
        if (!is_array($value)) {
            throw new InvalidArgumentException('Value must be an array of ShippingFieldReqDto objects');
        }

        return json_encode(array_map(function (ShippingFieldReqDto $dto) {
            return [
                'name'  => $dto->getName(),
                'value' => $dto->getValue(),
                'type'  => $dto->getType(),
            ];
        }, $value));
    }

    public function getName(): string
    {
        return self::SHIPPING_FIELD_REQ_DTO_ARRAY;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}

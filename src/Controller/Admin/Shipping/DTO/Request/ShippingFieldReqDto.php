<?php

declare(strict_types=1);

namespace App\Controller\Admin\Shipping\DTO\Request;

use App\Enum\Shipping\ShippingFieldEnum;
use Symfony\Component\Validator\Constraints as Assert;

class ShippingFieldReqDto
{
    #[Assert\NotBlank]
    private string $name;

    #[Assert\NotBlank]
    private string $value;

    #[Assert\NotBlank]
    #[Assert\Choice([
        ShippingFieldEnum::CITY->value,
        ShippingFieldEnum::DATETIME->value,
        ShippingFieldEnum::TEXTAREA->value
    ])]
    private string $type; // текстовое поле, дата и время, населённый пункт

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}

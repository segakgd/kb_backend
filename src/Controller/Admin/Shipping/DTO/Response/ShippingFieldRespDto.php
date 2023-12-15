<?php

namespace App\Controller\Admin\Shipping\DTO\Response;

class ShippingFieldRespDto
{
    private string $name;

    private string $value;

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

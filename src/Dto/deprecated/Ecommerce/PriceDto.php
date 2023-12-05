<?php

namespace App\Dto\deprecated\Ecommerce;

class PriceDto
{
    private ?int $value = null;
    private ?string $valueFraction = null;

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(?int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getValueFraction(): ?string
    {
        return $this->valueFraction;
    }

    public function setValueFraction(?string $valueFraction): self
    {
        $this->valueFraction = $valueFraction;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'value' => $this->getValue(),
            'valueFraction' => $this->getValueFraction(),
        ];
    }
}

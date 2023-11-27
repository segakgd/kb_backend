<?php

namespace App\Dto\Ecommerce;

class FieldsDto
{
    private ?FieldDto $value = null;

    public function getValue(): ?FieldDto
    {
        return $this->value;
    }

    public function setValue(?FieldDto $value): self
    {
        $this->value = $value;

        return $this;
    }
}

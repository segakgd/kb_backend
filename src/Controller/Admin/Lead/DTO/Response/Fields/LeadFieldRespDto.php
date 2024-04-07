<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead\DTO\Response\Fields;

class LeadFieldRespDto
{
    private string $name;

    private string $value;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): int|string
    {
        return $this->value;
    }

    public function setValue(int|string $value): self
    {
        $this->value = $value;

        return $this;
    }
}

<?php

namespace App\Dto\Admin\Lead\All;

class LeadContactRespDto
{
    private string $type;

    private string $name;

    private string|int $value;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getValue(): int|string
    {
        return $this->value;
    }

    public function setValue(int|string $value): void
    {
        $this->value = $value;
    }
}

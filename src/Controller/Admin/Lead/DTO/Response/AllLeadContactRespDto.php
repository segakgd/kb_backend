<?php

namespace App\Controller\Admin\Lead\DTO\Response;

class AllLeadContactRespDto
{
    private string $type;

    private string $name;

    private int|string $value;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

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

<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead\Request\Field;

use Symfony\Component\Validator\Constraints as Assert;

class LeadFieldReqDto
{
    private ?int $id = null;

    #[Assert\NotBlank]
    private string $name;

    #[Assert\NotBlank]
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }
}

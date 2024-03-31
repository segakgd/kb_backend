<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead\DTO\Request\Field;

use Symfony\Component\Validator\Constraints as Assert;

class LeadFieldReqDto
{
    #[Assert\NotBlank]
    private string $type; // todo -> DealField не содержит тип. Не вижу его применения.

    #[Assert\NotBlank]
    private string $name;

    #[Assert\NotBlank]
    private string|int $value; // todo -> не очень нравится string|int. Давайте уже стрингу хранить, а в нужных местах(?) типизировать в int. Entity со стрингой

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

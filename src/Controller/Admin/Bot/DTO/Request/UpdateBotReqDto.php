<?php

namespace App\Controller\Admin\Bot\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateBotReqDto
{
    private const AVAILABLE_MESSENGER = [
        'telegram',
        'vk',
    ];

    protected ?string $name = null;

    #[Assert\Choice(self::AVAILABLE_MESSENGER)]
    protected ?string $type = null;

    protected ?string $token = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }
}

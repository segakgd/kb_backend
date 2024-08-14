<?php

namespace App\Controller\Admin\Bot\Request;

use App\Service\Common\Bot\Enum\BotTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

class BotRequest
{
    private const AVAILABLE_MESSENGER = [
        'telegram',
        'vk',
    ];

    protected string $name;

    #[Assert\Choice(self::AVAILABLE_MESSENGER)]
    protected string $type;

    protected string $token;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getType(): BotTypeEnum
    {
        return BotTypeEnum::tryFrom($this->type);
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

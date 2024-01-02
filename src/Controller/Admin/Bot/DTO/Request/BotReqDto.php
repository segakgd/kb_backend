<?php

namespace App\Controller\Admin\Bot\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;

class BotReqDto
{
    private const AVAILABLE_MESSENGER = [
        'telegram',
        'vk',
    ];

    #[Assert\Choice(self::AVAILABLE_MESSENGER)]
    protected string $type;

    protected string $token;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }
}

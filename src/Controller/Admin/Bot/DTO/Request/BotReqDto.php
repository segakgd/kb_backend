<?php

namespace App\Controller\Admin\Bot\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class BotReqDto
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

//    #[Assert\Callback]
    public function validateToken(ExecutionContextInterface $context): void
    {
        if ($this->type === 'telegram') {
            if (!preg_match('/^\d{9}:[\w-]{35}$/', $this->token)) {
                $context
                    ->buildViolation('Invalid Telegram token format.')
                    ->atPath('token')
                    ->addViolation();
            }
        }
    }
}

<?php

namespace App\Service\Constructor\Core\Dto;

use App\Service\Common\Bot\Enum\BotTypeEnum;

class BotDto
{
    private string $type;

    private string $token;

    private string $chatId;

    public function getType(): BotTypeEnum
    {
        return BotTypeEnum::tryFrom($this->type);
    }

    public function setType(BotTypeEnum $type): static
    {
        $this->type = $type->value;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getChatId(): string
    {
        return $this->chatId;
    }

    public function setChatId(string $chatId): static
    {
        $this->chatId = $chatId;

        return $this;
    }
}

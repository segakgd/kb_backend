<?php

namespace App\Controller\Admin\Bot\Request;

use App\Service\Common\Bot\Enum\BotTypeEnum;

class UpdateBotRequest
{
    protected ?string $name = null;

    protected ?BotTypeEnum $type = null;

    protected ?string $token = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getType(): BotTypeEnum
    {
        return $this->type;
    }

    public function setType(BotTypeEnum $type): self
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

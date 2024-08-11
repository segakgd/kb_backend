<?php

namespace App\Service\Constructor\Core\Dto;

use App\Doctrine\DoctrineMappingInterface;
use App\Service\Common\Bot\Enum\BotTypeEnum;

class BotDto implements DoctrineMappingInterface
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

    public function toArray(): array
    {
        return [
            'type'   => $this->type,
            'token'  => $this->token,
            'chatId' => $this->chatId,
        ];
    }

    public static function fromArray(array $data): static
    {
        $dto = new self();
        $dto->setType(BotTypeEnum::tryFrom($data['type']));
        $dto->setToken($data['token']);
        $dto->setChatId($data['chatId']);

        return $dto;
    }
}

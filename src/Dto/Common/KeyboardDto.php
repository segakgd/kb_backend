<?php

namespace App\Dto\Common;

use App\Doctrine\DoctrineMappingInterface;

class KeyboardDto implements DoctrineMappingInterface
{
    private ?array $replyMarkup = null;

    public function getReplyMarkup(): ?array
    {
        return $this->replyMarkup;
    }

    public function getJsonReplyMarkup(): string
    {
        return $this->replyMarkup ? json_encode($this->replyMarkup) : '{}';
    }

    public function setReplyMarkup(?array $replyMarkup): static
    {
        $this->replyMarkup = $replyMarkup;

        return $this;
    }

    public static function fromArray(array $data): static
    {
        $keyboard = new static();
        $keyboard->setReplyMarkup($data['replyMarkup'] ?? null);

        return $keyboard;
    }

    public function toArray(): array
    {
        return [
            'replyMarkup' => $this->getReplyMarkup(),
        ];
    }
}

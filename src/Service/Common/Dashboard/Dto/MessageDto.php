<?php

namespace App\Service\Common\Dashboard\Dto;

use App\Service\Common\History\Enum\HistoryTypeEnum;

class MessageDto
{
    private int $id;

    private string $message;

    private array $images = [];

    private array $keyboard = [];

    private ?HistoryTypeEnum $type = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): static
    {
        $this->images = $images;

        return $this;
    }

    public function getKeyboard(): array
    {
        return $this->keyboard;
    }

    public function setKeyboard(array $keyboard): static
    {
        $this->keyboard = $keyboard;

        return $this;
    }

    public function getType(): ?HistoryTypeEnum
    {
        return $this->type;
    }

    public function setType(?HistoryTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }
}
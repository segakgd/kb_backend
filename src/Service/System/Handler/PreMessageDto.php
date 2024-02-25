<?php

namespace App\Service\System\Handler;

class PreMessageDto
{
    private string $message = '';

    private string $photo = '';

    private ?array $keyBoard = null;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getKeyBoard(): ?array
    {
        return $this->keyBoard;
    }

    public function setKeyBoard(?array $keyBoard): void
    {
        $this->keyBoard = $keyBoard;
    }
}

<?php

namespace App\Service\Constructor\Core\Dto;

use App\Dto\Responsible\ResponsibleMessageDto;

class Result implements ResultInterface
{
    private ?ResponsibleMessageDto $message = null;

    public function getMessage(): ?ResponsibleMessageDto
    {
        return $this->message;
    }

    public function setMessage(ResponsibleMessageDto $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function isEmptyMessage(): bool
    {
        return null === $this->message;
    }
}

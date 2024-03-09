<?php

namespace App\Service\System;

use App\Dto\Contract\ContractMessageDto;

class Contract
{
    public const GOTO_NEXT = 'next';

    public const GOTO_MAIN = 'main';

    private array $messages = [];

    private string $status; // todo не увернн

    private ?string $goto = null;

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function setMessages(array $messages): self
    {
        $this->messages = $messages;

        return $this;
    }

    public function addMessage(ContractMessageDto $message): self
    {
        $this->messages[] = $message;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): Contract
    {
        $this->status = $status;
        return $this;
    }

    public function getGoto(): ?string
    {
        return $this->goto;
    }

    public function setGoto(?string $goto): self
    {
        $this->goto = $goto;

        return $this;
    }
}

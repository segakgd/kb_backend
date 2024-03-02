<?php

namespace App\Service\System\Handler;

use App\Service\System\Handler\Dto\Contract\ContractMessageDto;

class Contract
{
    public const GOTO_NEXT = 'next';

    private array $messages = [];

    public ?string $goto = null;

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

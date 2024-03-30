<?php

namespace App\Service\System;

use App\Dto\Contract\ContractMessageDto;
use App\Entity\Visitor\VisitorEvent;
use App\Service\System\Resolver\ContractInterface;

class Contract implements ContractInterface
{
    public const GOTO_NEXT = 'next';

    public const GOTO_MAIN = 'main';

    private array $messages = [];

    private string $status; // todo не увернн

    private ?string $goto = null; // todo writing enum goto
    private array $data = [];

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

    public function isStatusDone(): bool
    {
        return $this->status === VisitorEvent::STATUS_DONE;
    }

    public function getGoto(): ?string
    {
        return $this->goto;
    }

    public function isNotGoto(): bool
    {
        return is_null($this->goto);
    }

    public function setGoto(?string $goto): self
    {
        $this->goto = $goto;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}

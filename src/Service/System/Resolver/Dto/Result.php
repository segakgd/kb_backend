<?php

namespace App\Service\System\Resolver\Dto;

use App\Dto\Contract\ContractMessageDto;

class Result implements ResultInterface
{
    private array $messages = [];

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function setMessages(array $messages): static
    {
        $this->messages = $messages;

        return $this;
    }

    public function addMessage(ContractMessageDto $message): static
    {
        $this->messages[] = $message;

        return $this;
    }
}

<?php

namespace App\Service\System\Resolver\Dto;

use App\Dto\Contract\ContractMessageDto;

interface ResultInterface
{
    public function getMessages(): array;

    public function setMessages(array $messages): static;

    public function addMessage(ContractMessageDto $message): static;
}

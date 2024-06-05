<?php

namespace App\Service\System\Resolver\Dto;

use App\Dto\Contract\ResponsibleMessageDto;

interface ResultInterface
{
    public function getMessages(): array;

    public function setMessages(array $messages): static;

    public function addMessage(ResponsibleMessageDto $message): static;
}

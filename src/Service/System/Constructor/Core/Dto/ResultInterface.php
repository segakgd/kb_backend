<?php

namespace App\Service\System\Constructor\Core\Dto;

use App\Dto\Responsible\ResponsibleMessageDto;

interface ResultInterface
{
    public function getMessages(): array;

    public function setMessages(array $messages): static;

    public function addMessage(ResponsibleMessageDto $message): static;

    public function isEmptyMessage(): bool;
}

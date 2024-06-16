<?php

namespace App\Service\Constructor\Core\Dto;

use App\Dto\Responsible\ResponsibleMessageDto;

interface ResultInterface
{
    public function getMessage(): ?ResponsibleMessageDto;

    public function setMessage(ResponsibleMessageDto $message): static;

    public function isEmptyMessage(): bool;
}

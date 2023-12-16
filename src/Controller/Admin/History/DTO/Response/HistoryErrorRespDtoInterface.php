<?php

namespace App\Controller\Admin\History\DTO\Response;

interface HistoryErrorRespDtoInterface
{
    public function getMessage(): string;

    public function setMessage(string $message): void;
}

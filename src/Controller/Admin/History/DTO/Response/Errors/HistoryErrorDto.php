<?php

namespace App\Controller\Admin\History\DTO\Response\Errors;

use App\Controller\Admin\History\DTO\Response\HistoryErrorRespDtoInterface;

class HistoryErrorDto implements HistoryErrorRespDtoInterface
{
    private string $message;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}

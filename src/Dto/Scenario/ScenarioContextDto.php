<?php

namespace App\Dto\Scenario;

class ScenarioContextDto
{
    private string $message;

    private array $replyMarkup;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getReplyMarkup(): array
    {
        return $this->replyMarkup;
    }

    public function setReplyMarkup(array $replyMarkup): void
    {
        $this->replyMarkup = $replyMarkup;
    }
}

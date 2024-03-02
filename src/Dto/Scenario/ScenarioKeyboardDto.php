<?php

namespace App\Dto\Scenario;

class ScenarioKeyboardDto
{
    private ?array $replyMarkup = null;

    public function getReplyMarkup(): ?array
    {
        return $this->replyMarkup;
    }

    public function getJsonReplyMarkup(): string
    {
        return $this->replyMarkup ? json_encode($this->replyMarkup) : '{}';
    }

    public function setReplyMarkup(?array $replyMarkup): static
    {
        $this->replyMarkup = $replyMarkup;

        return $this;
    }
}

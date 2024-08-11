<?php

namespace App\Service\Common\Dashboard\Dto;

class WebhookInfoDto
{
    private ?int $pendingUpdateCount;
    private ?int $lastErrorDate;
    private ?string $lastErrorMessage;

    public function getPendingUpdateCount(): ?int
    {
        return $this->pendingUpdateCount;
    }

    public function setPendingUpdateCount(?int $pendingUpdateCount): static
    {
        $this->pendingUpdateCount = $pendingUpdateCount;

        return $this;
    }

    public function getLastErrorDate(): ?int
    {
        return $this->lastErrorDate;
    }

    public function setLastErrorDate(?int $lastErrorDate): static
    {
        $this->lastErrorDate = $lastErrorDate;

        return $this;
    }

    public function getLastErrorMessage(): ?string
    {
        return $this->lastErrorMessage;
    }

    public function setLastErrorMessage(?string $lastErrorMessage): static
    {
        $this->lastErrorMessage = $lastErrorMessage;

        return $this;
    }
}
<?php

namespace App\Service\Common\Dashboard\Dto;

class ContractDto
{
    private array $chains = [];

    private bool $finished = false;

    public function getChains(): array
    {
        return $this->chains;
    }

    public function setChains(array $chains): static
    {
        $this->chains = $chains;

        return $this;
    }

    public function addChain(ActionDto $action): static
    {
        $this->chains[] = $action;

        return $this;
    }

    public function isFinished(): bool
    {
        return $this->finished;
    }

    public function setFinished(bool $finished): static
    {
        $this->finished = $finished;

        return $this;
    }
}
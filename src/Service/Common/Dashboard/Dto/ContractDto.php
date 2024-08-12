<?php

namespace App\Service\Common\Dashboard\Dto;

class ContractDto
{
    private array $actions = [];

    private bool $finished = false;

    public function getActions(): array
    {
        return $this->actions;
    }

    public function setActions(array $actions): static
    {
        $this->actions = $actions;

        return $this;
    }

    public function addAction(ActionDto $action): static
    {
        $this->actions[] = $action;

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
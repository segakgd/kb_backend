<?php

namespace App\Dto\Scenario;

class ScenarioStepDto
{
    private ?string $message = null;

    private ?ScenarioKeyboardDto $keyboard = null;

    /** @var array|null<ScenarioChainDto>  */
    private ?array $chain = null;

    private ?ScenarioAttachedDto $attached = null;

    private bool $finish = false;

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getKeyboard(): ?ScenarioKeyboardDto
    {
        return $this->keyboard;
    }

    public function setKeyboard(?ScenarioKeyboardDto $keyboard): static
    {
        $this->keyboard = $keyboard;

        return $this;
    }

    public function getChain(): ?array
    {
        return $this->chain;
    }

    public function addChain(ScenarioChainDto $chain): static
    {
        $this->chain[] = $chain;

        return $this;
    }

    public function setChain(?array $chain): static
    {
        $this->chain = $chain;

        return $this;
    }

    public function getAttached(): ?ScenarioAttachedDto
    {
        return $this->attached;
    }

    public function setAttached(?ScenarioAttachedDto $attached): static
    {
        $this->attached = $attached;

        return $this;
    }

    public function getFinish(): bool
    {
        return $this->finish;
    }

    public function setFinish(bool $finish): static
    {
        $this->finish = $finish;

        return $this;
    }
}

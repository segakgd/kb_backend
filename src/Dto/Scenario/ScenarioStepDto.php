<?php

namespace App\Dto\Scenario;

// booking
// contacts
class ScenarioStepDto
{
    private ?string $message = null;

    private ?ScenarioKeyboardDto $keyboard = null;

    /** @var array|null<ScenarioChainDto>  */
    private ?array $chain = null;

    private ScenarioAttachedDto $attached;

    private ?array $check = null;

    private bool $isFinish = false; // todo не нравится название

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

    public function getAttached(): ScenarioAttachedDto
    {
        return $this->attached;
    }

    public function setAttached(ScenarioAttachedDto $attached): static
    {
        $this->attached = $attached;

        return $this;
    }

    public function getCheck(): ?array
    {
        return $this->check;
    }

    public function addCheck(string $check): static
    {
        $this->check[] = $check;

        return $this;
    }

    public function isFinish(): bool
    {
        return $this->isFinish;
    }

    public function setIsFinish(bool $isFinish): static
    {
        $this->isFinish = $isFinish;

        return $this;
    }
}

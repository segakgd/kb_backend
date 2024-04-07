<?php

namespace App\Dto\Scenario;

use App\Entity\Scenario\Scenario;

class ScenarioStepDto
{
    private ?string $message = null;

    private ?ScenarioKeyboardDto $keyboard = null;

    /** @var array|null<ScenarioChainDto> */
    private ?array $chain = null;

    private ?ScenarioAttachedDto $attached = null;

    /** @deprecated delete it */
    private bool $finish = false;

    private ?Scenario $scenario = null;

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

    public function hasChain(): bool
    {
        return !empty($this->chain);
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

    /** @deprecated delete it */
    public function getFinish(): bool
    {
        return $this->finish;
    }

    /** @deprecated delete it */
    public function isNotFinish(): bool
    {
        return !$this->finish;
    }

    /** @deprecated delete it */
    public function isFinish(): bool
    {
        return $this->finish;
    }

    public function setFinish(bool $finish): static
    {
        $this->finish = $finish;

        return $this;
    }

    public function getScenario(): ?Scenario
    {
        return $this->scenario;
    }

    public function setScenario(?Scenario $scenario): static
    {
        $this->scenario = $scenario;

        return $this;
    }

    public static function fromArray(array $data): self
    {
        $step = new self();

        $step->setMessage($data['message'] ?? null);
        $step->setKeyboard(isset($data['keyboard']) ? ScenarioKeyboardDto::fromArray($data['keyboard']) : null);

        $chainData = $data['chain'] ?? null;
        $chain = [];

        if ($chainData !== null) {
            foreach ($chainData as $chainItem) {
                if (is_array($chainItem)) {
                    $chain[] = $chainItem;
                } else {
                    $chain[] = ScenarioChainDto::fromArray($chainItem);
                }
            }
        }

        $step->setChain($chain);

        $step->setAttached(isset($data['attached']) ? ScenarioAttachedDto::fromArray($data['attached']) : null);
        $step->setFinish($data['finish'] ?? false);

        return $step;
    }

    public function toArray(): array
    {
        $chainArray = [];
        $chain = $this->getChain() ?? [];

        /** @var ScenarioChainDto $chainItem */
        foreach ($chain as $chainItem) {
            if (is_array($chainItem)) {
                $chainArray[] = $chainItem;
            } else {
                $chainArray[] = $chainItem->toArray();
            }
        }

        return [
            'message' => $this->getMessage(),
            'keyboard' => $this->getKeyboard()?->toArray(),
            'chain' => $chainArray,
            'attached' => $this->getAttached()?->toArray(),
            'finish' => $this->getFinish(),
        ];
    }
}

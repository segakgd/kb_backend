<?php

namespace App\Dto\Scenario;

use App\Entity\Scenario\Scenario;

class ScenarioContractDto
{
    private ?string $message = null;

    private ?ScenarioKeyboardDto $keyboard = null;

    /** @var array|null<ScenarioActionDto> */
    private ?array $action = null;

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

    public function getAction(): ?array
    {
        return $this->action;
    }

    public function hasAction(): bool
    {
        return !empty($this->action);
    }

    public function addAction(ScenarioActionDto $action): static
    {
        $this->action[] = $action;

        return $this;
    }

    public function setAction(?array $action): static
    {
        $this->action = $action;

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
        $scenarioContractDto = new self();

        $scenarioContractDto->setMessage($data['message'] ?? null);
        $scenarioContractDto->setKeyboard(isset($data['keyboard']) ? ScenarioKeyboardDto::fromArray($data['keyboard']) : null);

        $actionData = $data['action'] ?? null;
        $action = [];

        if ($actionData !== null) {
            foreach ($actionData as $actionItem) {
                if (is_array($actionItem)) {
                    $action[] = $actionItem;
                } else {
                    $action[] = ScenarioActionDto::fromArray($actionItem);
                }
            }
        }

        $scenarioContractDto->setAction($action);

        $scenarioContractDto->setAttached(isset($data['attached']) ? ScenarioAttachedDto::fromArray($data['attached']) : null);
        $scenarioContractDto->setFinish($data['finish'] ?? false);

        return $scenarioContractDto;
    }

    public function toArray(): array
    {
        $actionArray = [];
        $action = $this->getAction() ?? [];

        /** @var ScenarioActionDto $actionItem */
        foreach ($action as $actionItem) {
            if (is_array($actionItem)) {
                $actionArray[] = $actionItem;
            } else {
                $actionArray[] = $actionItem->toArray();
            }
        }

        return [
            'message'  => $this->getMessage(),
            'keyboard' => $this->getKeyboard()?->toArray(),
            'action'   => $actionArray,
            'attached' => $this->getAttached()?->toArray(),
            'finish'   => $this->getFinish(),
        ];
    }
}

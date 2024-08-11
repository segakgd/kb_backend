<?php

namespace App\Dto\SessionCache\Cache;

use App\Doctrine\DoctrineMappingInterface;

class CacheContractDto implements DoctrineMappingInterface
{
    private ?string $message = null;

    private bool $finished = false;

    /** @var array<CacheActionDto> */
    private array $actions = [];

    private ?CacheKeyboardDto $keyboard = null;

    private ?CacheKeyboardDto $attached = null;

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

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

    public function getActions(): array
    {
        return $this->actions;
    }

    public function getCurrentAction(): ?CacheActionDto
    {
        if (empty($this->actions)) {
            return null;
        }

        foreach ($this->actions as $action) {
            if (!$action->isFinished()) {
                return $action;
            }
        }

        return null;
    }

    public function hasActions(): bool
    {
        return !empty($this->actions);
    }

    public function isAllActionsFinished(): bool
    {
        $unfinishedActions = array_filter($this->getActions(), fn (CacheActionDto $action) => !$action->isFinished());

        return empty($unfinishedActions);
    }

    public function setActions(array $actions): static
    {
        $this->actions = $actions;

        return $this;
    }

    public function addAction(CacheActionDto $action): static
    {
        $this->actions[] = $action;

        return $this;
    }

    /** @deprecated */
    public function isExistActions(): bool
    {
        return !empty($this->actions);
    }

    public function isEmptyActions(): bool
    {
        return empty($this->actions);
    }

    public function isEmptyKeyboard(): bool
    {
        return empty($this->getKeyboard()) || is_null($this->getKeyboard()->getReplyMarkup());
    }

    public function getKeyboard(): ?CacheKeyboardDto
    {
        return $this->keyboard;
    }

    public function setKeyboard(?CacheKeyboardDto $keyboard): static
    {
        $this->keyboard = $keyboard;

        return $this;
    }

    public function getAttached(): ?CacheKeyboardDto
    {
        return $this->attached;
    }

    public function setAttached(?CacheKeyboardDto $attached): static
    {
        $this->attached = $attached;

        return $this;
    }

    public static function fromArray(array $data): static
    {
        $cacheContractDto = new static();
        $cacheContractDto->finished = $data['finished'] ?? false;
        $cacheContractDto->message = $data['message'] ?? null;
        $cacheContractDto->keyboard = CacheKeyboardDto::fromArray($data['keyboard'] ?? []) ?? null;

        $actions = [];

        if (isset($data['actions'])) {
            foreach ($data['actions'] as $actionData) {
                $actions[] = CacheActionDto::fromArray($actionData);
            }
        }

        $cacheContractDto->actions = $actions;

        return $cacheContractDto;
    }

    public function toArray(): array
    {
        $actionsArray = [];

        foreach ($this->actions as $action) {
            $actionsArray[] = $action->toArray();
        }

        return [
            'message'  => $this->message,
            'finished' => $this->finished,
            'actions'  => $actionsArray,
        ];
    }
}

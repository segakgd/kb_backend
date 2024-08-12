<?php

namespace App\Dto\SessionCache\Cache;

use App\Doctrine\DoctrineMappingInterface;

class CacheActionDto implements DoctrineMappingInterface
{
    private string $target;

    private bool $finished;

    private bool $repeat;

    public function getTarget(): string
    {
        return $this->target;
    }

    public function setTarget(string $target): CacheActionDto
    {
        $this->target = $target;

        return $this;
    }

    public function isFinished(): bool
    {
        return $this->finished;
    }

    public function isNotFinished(): bool
    {
        return !$this->finished;
    }

    public function setFinished(bool $finished): static
    {
        $this->finished = $finished;

        return $this;
    }

    public function isRepeat(): bool
    {
        return $this->repeat;
    }

    public function setRepeat(bool $repeat): CacheActionDto
    {
        $this->repeat = $repeat;

        return $this;
    }

    public static function fromArray(array $data): static
    {
        $cacheAction = new static();

        $cacheAction->target = $data['target'] ?? 'undefined'; // todo убрать?
        $cacheAction->finished = $data['finished'] ?? false;
        $cacheAction->repeat = $data['repeat'] ?? false;

        return $cacheAction;
    }

    public function toArray(): array
    {
        return [
            'target'   => $this->target,
            'finished' => $this->finished,
            'repeat'   => $this->repeat,
        ];
    }
}

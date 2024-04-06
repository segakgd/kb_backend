<?php

namespace App\Dto\SessionCache\Cache;

use App\Enum\JumpEnum;

class CacheChainDto
{
    private JumpEnum $target;

    private bool $finished;

    private bool $repeat;

    public function getTarget(): JumpEnum
    {
        return $this->target;
    }

    public function setTarget(JumpEnum $target): static
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

    public function setRepeat(bool $repeat): CacheChainDto
    {
        $this->repeat = $repeat;
        return $this;
    }

    public static function fromArray(array $data): static
    {
        $cacheChain = new self();

        $cacheChain->target = JumpEnum::from($data['target']);
        $cacheChain->finished = $data['finished'] ?? false;
        $cacheChain->repeat = $data['repeat'] ?? false;

        return $cacheChain;
    }

    public function toArray(): array
    {
        return [
            'target' => $this->target->value,
            'finished' => $this->finished,
            'repeat' => $this->repeat,
        ];
    }
}

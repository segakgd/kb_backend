<?php

namespace App\Dto\SessionCache\Cache;

use App\Enum\GotoChainsEnum;

class CacheChainDto
{
    private GotoChainsEnum $target;

    private bool $finished;

    private bool $repeat;

    public function getTarget(): GotoChainsEnum
    {
        return $this->target;
    }

    public function setTarget(GotoChainsEnum $target): static
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
}

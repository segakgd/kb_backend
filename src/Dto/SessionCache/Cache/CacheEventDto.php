<?php

namespace App\Dto\SessionCache\Cache;

class CacheEventDto
{
    private bool $finished = false;

    /** @var array<CacheChainDto> */
    private array $chains = [];

    private ?CacheDataDto $data = null;

    public function __construct()
    {
        if (!$this->data) {
            $this->data = new CacheDataDto;
        }
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

    public function getChains(): array
    {
        return $this->chains;
    }

    public function getCurrentChain(): ?CacheChainDto
    {
        if (empty($this->chains)) {
            return null;
        }

        foreach ($this->chains as $chain) {
            if (!$chain->isFinished()) {
                return $chain;
            }
        }

        return null;
    }

    public function setChains(array $chains): static
    {
        $this->chains = $chains;

        return $this;
    }

    public function addChain(CacheChainDto $chain): static
    {
        $this->chains[] = $chain;

        return $this;
    }

    /** @deprecated */
    public function isExistChains(): bool
    {
        return !empty($this->chains);
    }

    public function isEmptyChains(): bool
    {
        return empty($this->chains);
    }

    public function getData(): CacheDataDto
    {
        return $this->data;
    }

    public function setData(CacheDataDto $data): static
    {
        $this->data = $data;

        return $this;
    }
}

<?php

namespace App\Dto\SessionCache\Cache;

class CacheEventDto
{
    private bool $finished = false;

    private array $chains = [];

    private ?CacheDataDto $data = null;

    public function __construct()
    {
        if (!$this->data) {
            $this->data = new CacheDataDto;
        }
    }

    public function isFinished(): string
    {
        return $this->finished;
    }

    public function setFinished(string $finished): static
    {
        $this->finished = $finished;

        return $this;
    }

    public function getChains(): array
    {
        return $this->chains;
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

    public function isExistChains(): bool
    {
        return !empty($this->chains);
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

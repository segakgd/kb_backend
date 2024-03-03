<?php

namespace App\Dto\SessionCache\Cache;

class CacheEventDto
{
    /** @deprecated Нужен? */
    private ?string $status = null;

    private array $chains = [];

    private ?CacheDataDto $data = null;

    public function __construct()
    {
        if (!$this->data) {
            $this->data = new CacheDataDto;
        }
    }

    /** @deprecated Нужен? */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /** @deprecated Нужен? */
    public function setStatus(?string $status): static
    {
        $this->status = $status;

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

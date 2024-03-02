<?php

namespace App\Service\System\Handler\Dto\Cache;

class CacheEventDto
{
    private string $status;

    private array $chains;

    private array $paginate;

    /** @deprecated */
    private array $statusChain;

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getChains(): array
    {
        return $this->chains;
    }

    public function setChains(array $chains): void
    {
        $this->chains = $chains;
    }

    public function getPaginate(): array
    {
        return $this->paginate;
    }

    public function setPaginate(array $paginate): void
    {
        $this->paginate = $paginate;
    }

    /** @deprecated */
    public function getStatusChain(): array
    {
        return $this->statusChain;
    }

    /** @deprecated */
    public function setStatusChain(array $statusChain): void
    {
        $this->statusChain = $statusChain;
    }
}

<?php

namespace App\Dto\SessionCache\Cache;

use App\Dto\Common\AbstractDto;

class CacheEventDto extends AbstractDto
{
    private bool $finished = false;

    /** @var array<CacheContractDto> */
    private array $contracts = [];

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

    public function getContracts(): array
    {
        return $this->contracts;
    }

    public function isEmptyContracts(): bool
    {
        return empty($this->contracts);
    }

    public function getCurrentContract(): ?CacheContractDto
    {
        if (empty($this->contracts)) {
            return null;
        }

        foreach ($this->contracts as $contract) {
            if (!$contract->isFinished()) {
                return $contract;
            }
        }

        return null;
    }

    public function setContracts(array $contracts): static
    {
        $this->contracts = $contracts;

        return $this;
    }

    public function addContract(CacheContractDto $contract): static
    {
        $this->contracts[] = $contract;

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

    public static function fromArray(array $data): static
    {
        $event = new self();
        $event->finished = $data['finished'] ?? false;

        $contracts = [];

        foreach ($data['contracts'] ?? [] as $value) {
            $contracts[] = CacheContractDto::fromArray($value);
        }

        $event->contracts = $contracts;

        $event->data = CacheDataDto::fromArray($data['data'] ?? []);

        return $event;
    }

    public function toArray(): array
    {
        $contractsArray = [];

        foreach ($this->contracts as $contract) {
            $contractsArray[] = $contract->toArray();
        }

        return [
            'finished' => $this->finished,
            'contracts' => $contractsArray,
            'data' => $this->data->toArray(),
        ];
    }
}

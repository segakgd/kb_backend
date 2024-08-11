<?php

namespace App\Dto\SessionCache\Cache;

use App\Doctrine\DoctrineMappingInterface;

class CacheEventDto implements DoctrineMappingInterface
{
    private bool $finished = false;

    private ?CacheContractDto $contract = null;

    private ?CacheDataDto $data = null;

    public function __construct()
    {
        if (!$this->contract) {
            $this->contract = new CacheContractDto();
        }

        if (!$this->data) {
            $this->data = new CacheDataDto();
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

    public function getContract(): CacheContractDto
    {
        return $this->contract;
    }

    public function setContract(CacheContractDto $contract): static
    {
        $this->contract = $contract;

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
        $event = new static();
        $event->finished = $data['finished'] ?? false;

        $event->contract = CacheContractDto::fromArray($data['contract']);
        $event->data = CacheDataDto::fromArray($data['data'] ?? []);

        return $event;
    }

    public function toArray(): array
    {
        return [
            'finished' => $this->finished,
            'contract' => $this->contract->toArray(),
            'data'     => $this->data->toArray(),
        ];
    }
}

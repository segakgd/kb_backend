<?php

namespace App\Service\Common\Dashboard\Dto;

use App\Enum\VisitorEventStatusEnum;
use DateTimeImmutable;

class EventDto
{
    private int $id;

    private string $type;

    private VisitorEventStatusEnum $status;

    private DateTimeImmutable $createdAt;

    private ContractDto $contract;

    private ?string $error;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): VisitorEventStatusEnum
    {
        return $this->status;
    }

    public function setStatus(VisitorEventStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getContract(): ContractDto
    {
        return $this->contract;
    }

    public function setContract(ContractDto $contract): static
    {
        $this->contract = $contract;

        return $this;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError(?string $error): static
    {
        $this->error = $error;

        return $this;
    }
}

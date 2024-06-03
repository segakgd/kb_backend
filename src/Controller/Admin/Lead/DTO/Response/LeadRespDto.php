<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead\DTO\Response;

use App\Controller\Admin\Lead\DTO\Response\Fields\LeadContactsRespDto;
use App\Controller\Admin\Lead\DTO\Response\Fields\LeadFieldRespDto;
use App\Controller\Admin\Lead\DTO\Response\Order\OrderRespDto;
use App\Enum\Lead\LeadStatusEnum;
use DateTimeImmutable;

class LeadRespDto
{
    private int $number;

    private LeadContactsRespDto $contacts;

    private array $fields;

    private string $status = 'new';

    private OrderRespDto $order;

    private DateTimeImmutable $createdAt;

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getContacts(): LeadContactsRespDto
    {
        return $this->contacts;
    }

    public function setContacts(LeadContactsRespDto $contacts): self
    {
        $this->contacts = $contacts;

        return $this;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function addField(LeadFieldRespDto $fields): self
    {
        $this->fields[] = $fields;

        return $this;
    }

    public function setFields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getOrder(): OrderRespDto
    {
        return $this->order;
    }

    public function setOrder(OrderRespDto $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}

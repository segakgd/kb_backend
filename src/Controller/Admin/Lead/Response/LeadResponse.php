<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead\Response;

use App\Controller\Admin\Lead\Response\Fields\LeadContactsResponse;
use App\Controller\Admin\Lead\Response\Fields\LeadFieldRespDto;
use App\Controller\Admin\Lead\Response\Order\OrderResponse;
use DateTimeImmutable;

class LeadResponse
{
    private int $number;

    private LeadContactsResponse $contacts;

    private array $fields;

    private string $status = 'new';

    private OrderResponse $order;

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

    public function getContacts(): LeadContactsResponse
    {
        return $this->contacts;
    }

    public function setContacts(LeadContactsResponse $contacts): self
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

    public function getOrder(): OrderResponse
    {
        return $this->order;
    }

    public function setOrder(OrderResponse $order): self
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

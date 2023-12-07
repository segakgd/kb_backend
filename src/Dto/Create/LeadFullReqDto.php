<?php

namespace App\Dto\Create;

class LeadFullReqDto
{
    private array $contacts;

    private array $fields;

    private OrderReqDto $order;

    public function getContacts(): array
    {
        return $this->contacts;
    }

    public function addContacts(LeadFieldReqDto $contact): void
    {
        $this->contacts[] = $contact;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function addFields(LeadFieldReqDto $field): void
    {
        $this->fields[] = $field;
    }

    public function getOrder(): OrderReqDto
    {
        return $this->order;
    }

    public function setOrder(OrderReqDto $order): void
    {
        $this->order = $order;
    }
}

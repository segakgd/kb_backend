<?php

namespace App\Dto\deprecated\Ecommerce;

class DealDto
{
    private ?ContactsDto $contacts = null;

    private ?array $fields = null;

    private ?OrderDto $order = null;

    public function getContacts(): ?ContactsDto
    {
        return $this->contacts;
    }

    public function setContacts(?ContactsDto $contacts): self
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * @return array<FieldDto>
     */
    public function getFields(): ?array
    {
        return $this->fields;
    }

    public function setFields(?array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function addField(FieldDto $field): self
    {
        $this->fields[] = $field;

        return $this;
    }

    public function getOrder(): ?OrderDto
    {
        return $this->order;
    }

    public function setOrder(?OrderDto $order): self
    {
        $this->order = $order;

        return $this;
    }
}

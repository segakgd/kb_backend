<?php

namespace App\Controller\Admin\Lead\DTO\Response;

use App\Controller\Admin\Lead\DTO\Response\Chanel\LeadChanelRespDto;
use App\Controller\Admin\Lead\DTO\Response\Fields\LeadContactsRespDto;
use App\Controller\Admin\Lead\DTO\Response\Fields\LeadFieldRespDto;
use App\Controller\Admin\Lead\DTO\Response\Order\OrderRespDto;
use App\Controller\Admin\Lead\DTO\Response\Script\LeadScriptRespDto;
use DateTimeImmutable;

class LeadRespDto
{
    private int $number;

    private LeadContactsRespDto $contacts;

    private array $fields;

    private string $status;

    private OrderRespDto $order;

    private LeadChanelRespDto $chanel;

    private LeadScriptRespDto $script;

    private DateTimeImmutable $createdAt;

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    public function getContacts(): LeadContactsRespDto
    {
        return $this->contacts;
    }

    public function setContacts(LeadContactsRespDto $contacts): void
    {
        $this->contacts = $contacts;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function addField(LeadFieldRespDto $fields): void
    {
        $this->fields[] = $fields;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getOrder(): OrderRespDto
    {
        return $this->order;
    }

    public function setOrder(OrderRespDto $order): void
    {
        $this->order = $order;
    }

    public function getChanel(): LeadChanelRespDto
    {
        return $this->chanel;
    }

    public function setChanel(LeadChanelRespDto $chanel): void
    {
        $this->chanel = $chanel;
    }

    public function getScript(): LeadScriptRespDto
    {
        return $this->script;
    }

    public function setScript(LeadScriptRespDto $script): void
    {
        $this->script = $script;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}

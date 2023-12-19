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
    public const LEAD_STATUS_NEW = 'new';

    public const LEAD_STATUS_PROCESS = 'process';

    public const LEAD_STATUS_SUSPENDED = 'suspended';

    public const LEAD_STATUS_REJECTED = 'rejected';

    public const LEAD_STATUS_SUCCESSFUL = 'successful';

    private int $number;

    private LeadContactsRespDto $contacts;

    private array $fields;

    private string $status; // new process suspended rejected successful

    private OrderRespDto $order;

    private LeadChanelRespDto $chanel;

    private LeadScriptRespDto $script;

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

    public function getChanel(): LeadChanelRespDto
    {
        return $this->chanel;
    }

    public function setChanel(LeadChanelRespDto $chanel): self
    {
        $this->chanel = $chanel;

        return $this;
    }

    public function getScript(): LeadScriptRespDto
    {
        return $this->script;
    }

    public function setScript(LeadScriptRespDto $script): self
    {
        $this->script = $script;

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

<?php

namespace App\Controller\Admin\Lead\DTO\Response;

use Symfony\Component\Validator\Constraints as Assert;

class AllLeadRespDto
{
    private const AVAILABLE_STATUSES = [
        'new',
        'process',
        'suspended',
        'rejected',
        'successful',
    ];

    private const AVAILABLE_TYPE = [
        'service',
        'product',
    ];

    private int $number;

    #[Assert\Choice(self::AVAILABLE_STATUSES)]
    private string $status;

    private string $fullName;

    private int $cost;

    private string $costWithFraction;

    private AllLeadContactsRespDto $contacts;

    #[Assert\Choice(self::AVAILABLE_TYPE)]
    private string $type;

    private bool $paymentStatus = false;

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

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

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function setCost(int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getCostWithFraction(): string
    {
        return $this->costWithFraction;
    }

    public function setCostWithFraction(string $costWithFraction): self
    {
        $this->costWithFraction = $costWithFraction;

        return $this;
    }

    public function getContacts(): AllLeadContactsRespDto
    {
        return $this->contacts;
    }

    public function setContacts(AllLeadContactsRespDto $contacts): self
    {
        $this->contacts = $contacts;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isPaymentStatus(): bool
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(bool $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }
}

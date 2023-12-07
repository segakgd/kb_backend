<?php

namespace App\Dto\Admin\Lead\Response;

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

    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function setCost(int $cost): void
    {
        $this->cost = $cost;
    }

    public function getCostWithFraction(): string
    {
        return $this->costWithFraction;
    }

    public function setCostWithFraction(string $costWithFraction): void
    {
        $this->costWithFraction = $costWithFraction;
    }

    public function getContacts(): AllLeadContactsRespDto
    {
        return $this->contacts;
    }

    public function setContacts(AllLeadContactsRespDto $contacts): void
    {
        $this->contacts = $contacts;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function isPaymentStatus(): bool
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(bool $paymentStatus): void
    {
        $this->paymentStatus = $paymentStatus;
    }
}

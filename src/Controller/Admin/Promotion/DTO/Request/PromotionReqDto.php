<?php

namespace App\Controller\Admin\Promotion\DTO\Request;

use DateTimeImmutable;

class PromotionReqDto
{
    private string $name;

    private string $type; // percent current

    private string $code;

    private int $triggersQuantity;

    private bool $isActive;

    private int $amount;

    private string $amountWithFraction;

    private DateTimeImmutable $activeFrom;

    private DateTimeImmutable $activeTo;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getTriggersQuantity(): int
    {
        return $this->triggersQuantity;
    }

    public function setTriggersQuantity(int $triggersQuantity): self
    {
        $this->triggersQuantity = $triggersQuantity;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmountWithFraction(): string
    {
        return $this->amountWithFraction;
    }

    public function setAmountWithFraction(string $amountWithFraction): self
    {
        $this->amountWithFraction = $amountWithFraction;

        return $this;
    }

    public function getActiveFrom(): DateTimeImmutable
    {
        return $this->activeFrom;
    }

    public function setActiveFrom(DateTimeImmutable $activeFrom): self
    {
        $this->activeFrom = $activeFrom;

        return $this;
    }

    public function getActiveTo(): DateTimeImmutable
    {
        return $this->activeTo;
    }

    public function setActiveTo(DateTimeImmutable $activeTo): self
    {
        $this->activeTo = $activeTo;

        return $this;
    }
}

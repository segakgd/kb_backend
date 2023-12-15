<?php

namespace App\Controller\Admin\Promotion\DTO\Response;

class PromotionRespDto
{
    private string $name;

    private string $type; // percent current

    private string $code;

    private int $triggersQuantity;

    private bool $isActive;

    private int $amount;

    private string $amountWithFraction;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getTriggersQuantity(): int
    {
        return $this->triggersQuantity;
    }

    public function setTriggersQuantity(int $triggersQuantity): void
    {
        $this->triggersQuantity = $triggersQuantity;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getAmountWithFraction(): string
    {
        return $this->amountWithFraction;
    }

    public function setAmountWithFraction(string $amountWithFraction): void
    {
        $this->amountWithFraction = $amountWithFraction;
    }
}

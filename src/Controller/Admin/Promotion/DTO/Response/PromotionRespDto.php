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
}

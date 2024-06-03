<?php

declare(strict_types=1);

namespace App\Controller\Admin\Promotion\DTO\Response;

use DateTimeInterface;

class PromotionRespDto
{
    private string $name;

    private string $discountType;

    private string $type; // percent current

    private string $code;

    private bool $usageWithAnyDiscount;

    private bool $isActive;

    private int $amount;

    private string $amountWithFraction;

    private ?DateTimeInterface $activeFrom = null;

    private ?DateTimeInterface $activeTo = null;

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

        $this->setAmountWithFraction(str_replace('.', ',', (string) ($amount * .01)));

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

    public function getDiscountType(): string
    {
        return $this->discountType;
    }

    public function setDiscountType(string $discountType): self
    {
        $this->discountType = $discountType;

        return $this;
    }

    public function getActiveFrom(): ?DateTimeInterface
    {
        return $this->activeFrom;
    }

    public function setActiveFrom(?DateTimeInterface $activeFrom): self
    {
        $this->activeFrom = $activeFrom;

        return $this;
    }

    public function getActiveTo(): ?DateTimeInterface
    {
        return $this->activeTo;
    }

    public function setActiveTo(?DateTimeInterface $activeTo): self
    {
        $this->activeTo = $activeTo;

        return $this;
    }

    public function isUsageWithAnyDiscount(): bool
    {
        return $this->usageWithAnyDiscount;
    }

    public function setUsageWithAnyDiscount(bool $usageWithAnyDiscount): self
    {
        $this->usageWithAnyDiscount = $usageWithAnyDiscount;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace App\Controller\Admin\Promotion\DTO\Request;

use App\Enum\Promotion\PromotionDiscountTypeEnum;
use App\Enum\Promotion\PromotionTypeEnum;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class PromotionReqDto
{
    private string $name;

    private string $code;

    #[Assert\Choice([PromotionTypeEnum::PROMO_CODE->value, PromotionTypeEnum::DISCOUNT->value])]
    private string $type;

    #[Assert\Choice([
        PromotionDiscountTypeEnum::PERCENT->value,
        PromotionDiscountTypeEnum::CURRENCY->value,
        PromotionDiscountTypeEnum::SHIPPING->value,
    ])]
    private string $discountType;

    #[Assert\GreaterThanOrEqual(0)]
    private int $amount;

    private ?int $count = null;

    private bool $isActive;

    private bool $usageWithAnyDiscount;

    private ?DateTimeInterface $activeFrom = null;

    private ?DateTimeInterface $activeTo = null;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if ($this->activeFrom !== null && $this->activeTo !== null && $this->activeFrom > $this->activeTo) {
            $context->buildViolation('The start date must be before or equal to the end date.')
                ->atPath('activeFrom')
                ->addViolation();
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function isUsageWithAnyDiscount(): bool
    {
        return $this->usageWithAnyDiscount;
    }

    public function setUsageWithAnyDiscount(bool $usageWithAnyDiscount): self
    {
        $this->usageWithAnyDiscount = $usageWithAnyDiscount;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): self
    {
        $this->count = $count;

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

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}

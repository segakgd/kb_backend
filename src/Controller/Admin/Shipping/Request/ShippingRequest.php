<?php

declare(strict_types=1);

namespace App\Controller\Admin\Shipping\Request;

use App\Dto\Ecommerce\Shipping\ShippingPriceDto;
use App\Enum\Shipping\ShippingCalculationTypeEnum;
use App\Enum\Shipping\ShippingTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ShippingRequest
{
    #[Assert\NotBlank]
    private string $title;

    #[Assert\NotBlank]
    private string $description;

    #[Assert\Choice([ShippingTypeEnum::COURIER->value, ShippingTypeEnum::PICK_UP->value])]
    private string $type; // самовывоз, курьером

    #[Assert\Choice([ShippingCalculationTypeEnum::CURRENCY->value, ShippingCalculationTypeEnum::PERCENT->value])]
    private string $calculationType; // В проценнах, В валюте

    #[Assert\Valid]
    private ?ShippingPriceDto $price = null;

    #[Assert\NotBlank]
    private bool $isActive;

    private ?int $applyFromAmount = null;

    private ?int $applyToAmount = null;

    private ?int $freeFrom = null;

    private bool $isNotFixed = false;

    #[Assert\Valid]
    private array $fields;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if ($this->applyFromAmount !== null && $this->applyToAmount !== null && $this->applyFromAmount > $this->applyToAmount) {
            $context
                ->buildViolation('apply period has logical error')
                ->addViolation();
        }

        if (!$this->isNotFixed && $this->price === null) {
            $context
                ->buildViolation('price not fixed')
                ->addViolation();
        }

        if ($this->applyToAmount !== null && $this->applyToAmount < 0 || $this->applyFromAmount !== null && $this->applyFromAmount < 0) {
            $context
                ->buildViolation('applyFromAmount and applyToAmount must be greater than 0')
                ->addViolation();
        }

        if ($this->freeFrom !== null && $this->freeFrom < 0) {
            $context
                ->buildViolation('freeFrom must be greater than 0')
                ->addViolation();
        }
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getCalculationType(): string
    {
        return $this->calculationType;
    }

    public function setCalculationType(string $calculationType): self
    {
        $this->calculationType = $calculationType;

        return $this;
    }

    public function getApplyFromAmount(): ?int
    {
        return $this->applyFromAmount;
    }

    public function setApplyFromAmount(?int $applyFromAmount): self
    {
        $this->applyFromAmount = $applyFromAmount;

        return $this;
    }

    public function getPrice(): ?ShippingPriceDto
    {
        return $this->price;
    }

    public function setPrice(?ShippingPriceDto $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getApplyToAmount(): ?int
    {
        return $this->applyToAmount;
    }

    public function setApplyToAmount(?int $applyToAmount): self
    {
        $this->applyToAmount = $applyToAmount;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function addFields(ShippingFieldRequest $field): self
    {
        $this->fields[] = $field;

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

    public function getFreeFrom(): ?int
    {
        return $this->freeFrom;
    }

    public function setFreeFrom(?int $freeFrom): self
    {
        $this->freeFrom = $freeFrom;

        return $this;
    }

    public function isNotFixed(): bool
    {
        return $this->isNotFixed;
    }

    public function setIsNotFixed(bool $isNotFixed): self
    {
        $this->isNotFixed = $isNotFixed;

        return $this;
    }
}

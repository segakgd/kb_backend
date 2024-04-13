<?php

declare(strict_types=1);

namespace App\Controller\Admin\Shipping\DTO\Request;

use App\Dto\Ecommerce\Shipping\ShippingPriceDto;
use App\Enum\Shipping\ShippingCalculationTypeEnum;
use App\Enum\Shipping\ShippingTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

class ShippingReqDto
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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getCalculationType(): string
    {
        return $this->calculationType;
    }

    public function setCalculationType(string $calculationType): void
    {
        $this->calculationType = $calculationType;
    }

    public function getApplyFromAmount(): int
    {
        return $this->applyFromAmount;
    }

    public function setApplyFromAmount(int $applyFromAmount): void
    {
        $this->applyFromAmount = $applyFromAmount;
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

    public function getApplyToAmount(): int
    {
        return $this->applyToAmount;
    }

    public function setApplyToAmount(int $applyToAmount): void
    {
        $this->applyToAmount = $applyToAmount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function addFields(ShippingFieldReqDto $field): void
    {
        $this->fields[] = $field;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
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

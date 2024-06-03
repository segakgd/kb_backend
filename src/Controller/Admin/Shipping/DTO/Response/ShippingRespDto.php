<?php

declare(strict_types=1);

namespace App\Controller\Admin\Shipping\DTO\Response;

use App\Dto\Ecommerce\Shipping\ShippingPriceDto;

class ShippingRespDto
{
    private string $name;

    private string $type;

    private string $calculationType;

    private ?ShippingPriceDto $price;

//    private string $amountWF;

    private ?int $applyFromAmount;

    private string $applyFromAmountWF;

    private ?int $applyToAmount;

    private string $applyToAmountWF;

    private string $description;

    private array $fields;

    private bool $isActive;

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

        if ($applyFromAmount !== null) {
            // todo -> необязательно делать реплейс, у каждой локали свои особенности
            $this->setApplyFromAmountWF(str_replace('.', ',', (string)($applyFromAmount * 0.01)));
        }

        return $this;
    }

    public function getApplyFromAmountWF(): string
    {
        return $this->applyFromAmountWF;
    }

    public function setApplyFromAmountWF(string $applyFromAmountWF): self
    {
        $this->applyFromAmountWF = $applyFromAmountWF;

        return $this;
    }

    public function getApplyToAmount(): ?int
    {
        return $this->applyToAmount;
    }

    public function setApplyToAmount(?int $applyToAmount): self
    {
        $this->applyToAmount = $applyToAmount;

        if ($applyToAmount !== null) {
            $this->setApplyToAmountWF(str_replace('.', ',', (string)($applyToAmount * 0.01)));
        }

        return $this;
    }

    public function getApplyToAmountWF(): string
    {
        return $this->applyToAmountWF;
    }

    public function setApplyToAmountWF(string $applyToAmountWF): self
    {
        $this->applyToAmountWF = $applyToAmountWF;

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

    public function addFields(ShippingFieldRespDto $field): self
    {
        $this->fields[] = $field;

        return $this;
    }

    public function setFields(array $fields): self
    {
        $this->fields = $fields;

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

    public function getPrice(): ?ShippingPriceDto
    {
        return $this->price;
    }

    public function setPrice(?ShippingPriceDto $price): self
    {
        $this->price = $price;

        return $this;
    }
}

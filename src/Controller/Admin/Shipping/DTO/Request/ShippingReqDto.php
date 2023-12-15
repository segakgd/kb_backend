<?php

namespace App\Controller\Admin\Shipping\DTO\Request;

class ShippingReqDto
{
    private string $name;

    private string $type; // самовывоз, курьером

    private string $calculationType; // В проценнах, В валюте

    private int $amount;

    private string $amountWF;

    private int $applyFromAmount;

    private string $applyFromAmountWF;

    private int $applyToAmount;

    private string $applyToAmountWF;

    private string $description;

    private array $fields;

    private bool $isActive;


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

    public function getCalculationType(): string
    {
        return $this->calculationType;
    }

    public function setCalculationType(string $calculationType): void
    {
        $this->calculationType = $calculationType;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getAmountWF(): string
    {
        return $this->amountWF;
    }

    public function setAmountWF(string $amountWF): void
    {
        $this->amountWF = $amountWF;
    }

    public function getApplyFromAmount(): int
    {
        return $this->applyFromAmount;
    }

    public function setApplyFromAmount(int $applyFromAmount): void
    {
        $this->applyFromAmount = $applyFromAmount;
    }

    public function getApplyFromAmountWF(): string
    {
        return $this->applyFromAmountWF;
    }

    public function setApplyFromAmountWF(string $applyFromAmountWF): void
    {
        $this->applyFromAmountWF = $applyFromAmountWF;
    }

    public function getApplyToAmount(): int
    {
        return $this->applyToAmount;
    }

    public function setApplyToAmount(int $applyToAmount): void
    {
        $this->applyToAmount = $applyToAmount;
    }

    public function getApplyToAmountWF(): string
    {
        return $this->applyToAmountWF;
    }

    public function setApplyToAmountWF(string $applyToAmountWF): void
    {
        $this->applyToAmountWF = $applyToAmountWF;
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
}

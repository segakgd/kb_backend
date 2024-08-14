<?php

namespace App\Controller\Admin\Lead\Response\Order\Promotion;

class PromotionRespDto
{
    public const CALCULATION_TYPE_PERCENT = 'percent';

    public const CALCULATION_TYPE_FIXED = 'fixed';

    private string $name;

    /** Могут быть как percent так и fixed */
    private string $calculationType;

    private string $code;

    /** Могут быть как проценты, так и валюта (зависит от типа) */
    private int $discount = 0;

    private int $totalAmount = 0;

    private string $totalAmountWF = '0';

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDiscount(): int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getTotalAmountWF(): string
    {
        return $this->totalAmountWF;
    }

    public function setTotalAmountWF(string $totalAmountWithFraction): self
    {
        $this->totalAmountWF = $totalAmountWithFraction;

        return $this;
    }
}

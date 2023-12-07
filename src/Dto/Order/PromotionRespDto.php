<?php

namespace App\Dto\Order;

class PromotionRespDto
{
    private string $name;

    /** Могут быть как percent так и fixed */
    private string $calculationType;

    private string $code;

    /** Могут быть как проценты, так и валюта (зависит от типа) */
    private int $discount = 0;

    private int $totalAmount = 0;

    private string $totalAmountWithFraction = '0';

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCalculationType(): string
    {
        return $this->calculationType;
    }

    public function setCalculationType(string $calculationType): void
    {
        $this->calculationType = $calculationType;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getDiscount(): int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): void
    {
        $this->discount = $discount;
    }

    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function getTotalAmountWithFraction(): string
    {
        return $this->totalAmountWithFraction;
    }

    public function setTotalAmountWithFraction(string $totalAmountWithFraction): void
    {
        $this->totalAmountWithFraction = $totalAmountWithFraction;
    }
}

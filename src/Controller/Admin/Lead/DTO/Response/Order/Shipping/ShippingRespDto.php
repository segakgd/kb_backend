<?php

namespace App\Controller\Admin\Lead\DTO\Response\Order\Shipping;

class ShippingRespDto
{
    private string $name;

    /** courier - курьер, pickup - самовывоз */
    private string $type = 'pickup';

    private int $totalAmount = 0;

    private string $totalAmountWithFraction = '0'; // todo WF

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

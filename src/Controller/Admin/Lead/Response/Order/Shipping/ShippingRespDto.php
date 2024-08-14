<?php

namespace App\Controller\Admin\Lead\Response\Order\Shipping;

class ShippingRespDto
{
    public const TYPE_COURIER = 'courier';

    public const TYPE_PICKUP = 'pickup';

    private string $name;

    /** courier - курьер, pickup - самовывоз */
    private string $type = 'pickup';

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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

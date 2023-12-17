<?php

namespace App\Controller\Admin\Lead\DTO\Request\Order\Shipping;

class OrderShippingReqDto
{
    private int $id;

    private int $totalAmount = 0;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }
}

<?php

namespace App\Dto\Create;

use App\Dto\one\PaymentRespDto;
use App\Dto\one\ProductRespDto;
use App\Dto\one\PromotionRespDto;
use App\Dto\one\ShippingRespDto;
use DateTimeImmutable;

class OrderPromotionReqDto
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

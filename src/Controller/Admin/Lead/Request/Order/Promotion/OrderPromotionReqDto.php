<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead\Request\Order\Promotion;

use Symfony\Component\Validator\Constraints as Assert;

class OrderPromotionReqDto
{
    #[Assert\GreaterThan(0)]
    private int $id;

    private int $totalAmount = 0;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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
}

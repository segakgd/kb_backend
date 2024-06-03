<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead\DTO\Request\Order\Product;

use Symfony\Component\Validator\Constraints as Assert;

class OrderVariantReqDto
{
    #[Assert\GreaterThan(0)]
    private int $id;

    private ?int $count = null;

    private int $price;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}

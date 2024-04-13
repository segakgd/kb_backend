<?php

declare(strict_types=1);

namespace App\Dto\Ecommerce\Shipping;

use Symfony\Component\Validator\Constraints as Assert;

class ShippingPriceDto
{
    #[Assert\GreaterThanOrEqual(0)]
    private int $price;

    #[Assert\NotBlank]
    private string $currency;

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'price' => $this->price,
            'currency' => $this->currency,
        ];
    }

    public static function fromArray(array $data): self
    {
        $dto = new self();
        $dto->setPrice($data['price']);
        $dto->setCurrency($data['currency']);

        return $dto;
    }
}

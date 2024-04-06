<?php

declare(strict_types=1);

namespace App\Dto\Product\Variants;

use Symfony\Component\Validator\Constraints as Assert;

class VariantPriceDto
{
    #[Assert\GreaterThanOrEqual(0)]
    private float $cost;

    #[Assert\NotBlank]
    private string $currency;

    public function getCost(): float
    {
        return $this->cost;
    }

    public function setCost(float $cost): self
    {
        $this->cost = $cost;

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
            'cost' => $this->cost,
            'currency' => $this->currency,
        ];
    }

    public static function fromArray(array $data): self
    {
        $dto = new self();
        $dto->setCost($data['cost']);
        $dto->setCurrency($data['currency']);

        return $dto;
    }
}

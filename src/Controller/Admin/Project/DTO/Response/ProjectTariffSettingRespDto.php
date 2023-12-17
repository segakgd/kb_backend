<?php

namespace App\Controller\Admin\Project\DTO\Response;

class ProjectTariffSettingRespDto
{
    private string $name;

    private int $price;

    private string $priceWithFraction;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getPriceWithFraction(): string
    {
        return $this->priceWithFraction;
    }

    public function setPriceWithFraction(string $priceWithFraction): void
    {
        $this->priceWithFraction = $priceWithFraction;
    }
}

<?php

namespace App\Controller\Admin\Project\DTO\Response;

class ProjectTariffSettingRespDto
{
    private string $name;

    private int $price;

    private string $priceWF;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getPriceWF(): string
    {
        return $this->priceWF;
    }

    public function setPriceWF(string $priceWithFraction): self
    {
        $this->priceWF = $priceWithFraction;

        return $this;
    }
}

<?php

namespace App\Controller\Admin\Project\DTO\Response;

class TariffSettingRespDto
{
    private string $name;

    private string $code;

    private int $price;

    private string $priceWF;

    private string $description;

    private bool $active;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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

    public function setPriceWF(string $priceWF): self
    {
        $this->priceWF = $priceWF;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}

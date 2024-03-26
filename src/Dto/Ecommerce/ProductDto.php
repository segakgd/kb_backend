<?php

namespace App\Dto\Ecommerce;

class ProductDto
{
    public string $variantParentId;

    public string $name;

    public string $count;

    public string $price;

    public function getVariantParentId(): string
    {
        return $this->variantParentId;
    }

    public function setVariantParentId(string $variantParentId): ProductDto
    {
        $this->variantParentId = $variantParentId;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ProductDto
    {
        $this->name = $name;
        return $this;
    }

    public function getCount(): string
    {
        return $this->count;
    }

    public function setCount(string $count): ProductDto
    {
        $this->count = $count;
        return $this;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): ProductDto
    {
        $this->price = $price;
        return $this;
    }
}

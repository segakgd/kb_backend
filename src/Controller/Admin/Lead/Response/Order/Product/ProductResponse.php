<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead\Response\Order\Product;

class ProductResponse
{
    private string $name;

    private ProductVariantResponse $variant;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getVariant(): ProductVariantResponse
    {
        return $this->variant;
    }

    public function setVariant(ProductVariantResponse $variant): self
    {
        $this->variant = $variant;

        return $this;
    }
}

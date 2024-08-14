<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead\Response\Order\Product;

class ProductRespDto
{
    private string $name;

    private ProductVariantRespDto $variant;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getVariant(): ProductVariantRespDto
    {
        return $this->variant;
    }

    public function setVariant(ProductVariantRespDto $variant): self
    {
        $this->variant = $variant;

        return $this;
    }
}

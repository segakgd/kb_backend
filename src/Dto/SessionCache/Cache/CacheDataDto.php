<?php

namespace App\Dto\SessionCache\Cache;

class CacheDataDto
{
    private ?int $pageNow = null;

    private ?int $productId = null;

    private ?int $variantId = null;

    private ?string $categoryName = null;

    private ?int $categoryId = null;

    public function getPageNow(): ?int
    {
        return $this->pageNow;
    }

    public function setPageNow(?int $pageNow): static
    {
        $this->pageNow = $pageNow;

        return $this;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(?int $productId): static
    {
        $this->productId = $productId;

        return $this;
    }

    public function getVariantId(): ?int
    {
        return $this->variantId;
    }

    public function setVariantId(?int $variantId): CacheDataDto
    {
        $this->variantId = $variantId;
        return $this;
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setCategoryName(?string $categoryName): CacheDataDto
    {
        $this->categoryName = $categoryName;
        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(?int $categoryId): CacheDataDto
    {
        $this->categoryId = $categoryId;
        return $this;
    }
}

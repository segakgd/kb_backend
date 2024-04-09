<?php

namespace App\Dto\SessionCache\Cache;

use App\Dto\Common\AbstractDto;

class CacheDataDto extends AbstractDto
{
    private ?int $pageNow = null;

    private ?int $productId = null;

    private ?int $variantId = null;

    private ?int $count = null;

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

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): CacheDataDto
    {
        $this->count = $count;
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

    public static function fromArray(array $data): static
    {
        $cacheData = new self();
        $cacheData->pageNow = $data['pageNow'] ?? null;
        $cacheData->productId = $data['productId'] ?? null;
        $cacheData->variantId = $data['variantId'] ?? null;
        $cacheData->count = $data['count'] ?? null;
        $cacheData->categoryName = $data['categoryName'] ?? null;
        $cacheData->categoryId = $data['categoryId'] ?? null;
        return $cacheData;
    }

    public function toArray(): array
    {
        return [
            'pageNow' => $this->pageNow,
            'productId' => $this->productId,
            'variantId' => $this->variantId,
            'count' => $this->count,
            'categoryName' => $this->categoryName,
            'categoryId' => $this->categoryId,
        ];
    }
}

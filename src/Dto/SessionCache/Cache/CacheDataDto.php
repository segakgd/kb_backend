<?php

namespace App\Dto\SessionCache\Cache;

class CacheDataDto
{
    private ?int $pageNow = null;

    private ?int $productId = null;

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
}

<?php

namespace App\Dto\deprecated\Ecommerce;

use DateTimeImmutable;

class ProductDto
{
    private ?int $id = null;

    private ?int $projectId = null;

    /** @var array<ProductCategoryDto> */
    private array $categories = [];

    /** @var array<ProductVariantDto> */
    private array $variants = [];

    private ?DateTimeImmutable $createdAt = null;

    private ?DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        if ($this->createdAt === null){
            $this->createdAt = new DateTimeImmutable();
        }
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getProjectId(): ?int
    {
        return $this->projectId;
    }

    public function setProjectId(?int $projectId): self
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function addCategory(ProductCategoryDto $category): self
    {
        $this->categories[] = $category;

        return $this;
    }

    public function getVariants(): array
    {
        return $this->variants;
    }

    public function addVariant(ProductVariantDto $variants): self
    {
        $this->variants[] = $variants;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}

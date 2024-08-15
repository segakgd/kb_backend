<?php

declare(strict_types=1);

namespace App\Controller\Admin\Product\DTO\Response;

use App\Controller\Admin\ProductCategory\Response\ProductCategoryResponse;

class ProductResponse
{
    private int $id;

    private string $name;

    private bool $visible;

    private string $description;

    private array $categories;

    private array $variants;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

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

    public function setCategories(array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function addCategory(ProductCategoryResponse $category): self
    {
        $this->categories[] = $category;

        return $this;
    }

    public function setVariants(array $variants): self
    {
        $this->variants = $variants;

        return $this;
    }

    public function getVariants(): array
    {
        return $this->variants;
    }

    public function addVariants(ProductVariantResponse $variant): self
    {
        $this->variants[] = $variant;

        return $this;
    }
}

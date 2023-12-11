<?php

namespace App\Dto\Admin\Product\Request;

class ProductReqDto
{
    private string $name;

    private string $article;

    private string $type;

    private bool $visible;

    private string $description;

    private string $image;

    private array $category;

    private array $variants;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getArticle(): string
    {
        return $this->article;
    }

    public function setArticle(string $article): void
    {
        $this->article = $article;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getCategory(): array
    {
        return $this->category;
    }

    public function addCategory(ProductCategoryReqDto $category): void
    {
        $this->category[] = $category;
    }

    public function getVariants(): array
    {
        return $this->variants;
    }

    public function addVariants(ProductVariantReqDto $variant): void
    {
        $this->variants[] = $variant;
    }
}

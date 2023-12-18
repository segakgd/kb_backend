<?php

namespace App\Controller\Admin\Product\DTO\Request;

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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getArticle(): string
    {
        return $this->article;
    }

    public function setArticle(string $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCategory(): array
    {
        return $this->category;
    }

    public function addCategory(ProductCategoryReqDto $category): self
    {
        $this->category[] = $category;

        return $this;
    }

    public function getVariants(): array
    {
        return $this->variants;
    }

    public function addVariants(ProductVariantReqDto $variant): self
    {
        $this->variants[] = $variant;

        return $this;
    }
}

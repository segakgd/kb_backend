<?php

namespace App\Controller\Admin\Product\DTO\Response;

use App\Dto\Product\Variants\ImageDto;

class ProductRespDto
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

    public function getImage(): array
    {
        return $this->images;
    }

    public function setImage(array $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function addImage(ImageDto $imageDto): self
    {
        $this->images[] = $imageDto;

        return $this;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function addCategory(ProductCategoryRespDto $category): self
    {
        $this->categories[] = $category;

        return $this;
    }

    public function getVariants(): array
    {
        return $this->variants;
    }

    public function addVariants(ProductVariantRespDto $variant): self
    {
        $this->variants[] = $variant;

        return $this;
    }
}

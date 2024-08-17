<?php

declare(strict_types=1);

namespace App\Controller\Admin\Product\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ProductRequest
{
    #[Assert\NotBlank]
    private string $name;

    #[Assert\Choice(choices: [true, false])]
    private bool $visible;

    private ?string $description = null;

    #[Assert\Valid]
    private array $categories = [];

    #[Assert\Valid]
    private array $variants = [];

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setCategories(array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /** @return ProductCategoryRequest[] */
    public function getCategories(): array
    {
        return $this->categories;
    }

    public function addCategory(ProductCategoryRequest $category): self
    {
        $this->categories[] = $category;

        return $this;
    }

    public function setVariants(array $variants): self
    {
        $this->variants = $variants;

        return $this;
    }

    /**
     * @return ProductVariantRequest[]
     */
    public function getVariants(): array
    {
        return $this->variants;
    }

    public function addVariant(ProductVariantRequest $variant): self
    {
        $this->variants[] = $variant;

        return $this;
    }
}

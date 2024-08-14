<?php

declare(strict_types=1);

namespace App\Controller\Admin\Product\DTO\Request;

use App\Dto\Ecommerce\Product\Variants\ImageDto;
use App\Dto\Ecommerce\Product\Variants\VariantPriceDto;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProductVariantRequest
{
    private ?int $id = null;

    #[Assert\Length(max: 50)]
    private string $article;

    #[Assert\NotBlank]
    private string $name;

    private ?int $count = null;

    private bool $isLimitless = false;

    #[Assert\Valid]
    private array $price = [];

    private array $images = [];

    private bool $isActive = false;

    #[Assert\Callback]
    public function validateVariantCount(ExecutionContextInterface $context): void
    {
        if (false === $this->isLimitless && null === $this->count) {
            $context
                ->buildViolation('Count of variant not defined')
                ->addViolation();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
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

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getPrice(): array
    {
        return $this->price;
    }

    public function setPrice(array $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function addPrice(VariantPriceDto $priceDto): self
    {
        $this->price[] = $priceDto;

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

    public function addImage(ImageDto $imageDto): self
    {
        $this->images[] = $imageDto;

        return $this;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isLimitless(): bool
    {
        return $this->isLimitless;
    }

    public function setIsLimitless(bool $isLimitless): self
    {
        $this->isLimitless = $isLimitless;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace App\Controller\Admin\Product\DTO\Request;

use App\Dto\Product\Variants\ImageDto;
use App\Dto\Product\Variants\VariantPriceDto;
use Symfony\Component\Validator\Constraints as Assert;

class ProductVariantReqDto
{
    private ?int $id;

    #[Assert\Length(max: 50)]
    private string $article;

    #[Assert\NotBlank]
    private string $name;

    #[Assert\GreaterThan(0)]
    private ?int $count;

    #[Assert\Valid]
    private array $price = [];

    private array $images = [];

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
}

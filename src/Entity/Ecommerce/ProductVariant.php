<?php

declare(strict_types=1);

namespace App\Entity\Ecommerce;

use App\Doctrine\Types\VariantPriceType;
use App\Dto\Product\Variants\VariantPriceDto;
use App\Repository\Ecommerce\ProductVariantRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductVariantRepository::class)]
class ProductVariant
{
    // todo нужно добавить возможность резервировать товар резерв
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $article = null;

    #[ORM\Column(nullable: true)]
    private array $image = [];

    #[ORM\Column(type: VariantPriceType::VARIANT_PRICE_TYPE)]
    private array $price = [];

    #[ORM\Column(nullable: true)]
    private ?int $count = null;

    #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'variants')]
    private ?Product $product = null;

    #[ORM\Column(nullable: true)]
    private ?bool $promotionDistributed = null;

    #[ORM\Column(nullable: true)]
    private ?int $percentDiscount = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    #[ORM\Column(nullable: false)]
    private bool $isLimitless = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $activeFrom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $activeTo = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        if ($this->createdAt === null) {
            $this->createdAt = new DateTimeImmutable();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function setArticle(string $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): array
    {
        return $this->image;
    }

    public function setImage(?array $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getPrice(): array
    {
        return $this->price;
    }

    public function setPrice(array $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function addPrice(VariantPriceDto $price): static
    {
        $this->price[] = $price;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): static
    {
        $this->count = $count;

        return $this;
    }

    public function isPromotionDistributed(): ?bool
    {
        return $this->promotionDistributed;
    }

    public function setPromotionDistributed(bool $promotionDistributed): static
    {
        $this->promotionDistributed = $promotionDistributed;

        return $this;
    }

    public function getPercentDiscount(): ?int
    {
        return $this->percentDiscount;
    }

    public function setPercentDiscount(?int $percentDiscount): static
    {
        $this->percentDiscount = $percentDiscount;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getActiveFrom(): ?DateTimeInterface
    {
        return $this->activeFrom;
    }

    public function setActiveFrom(?DateTimeInterface $activeFrom): static
    {
        $this->activeFrom = $activeFrom;

        return $this;
    }

    public function getActiveTo(): ?DateTimeInterface
    {
        return $this->activeTo;
    }

    public function setActiveTo(?DateTimeInterface $activeTo): static
    {
        $this->activeTo = $activeTo;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function markAsUpdated(): static
    {
        return $this->setUpdatedAt(new DateTimeImmutable());
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

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

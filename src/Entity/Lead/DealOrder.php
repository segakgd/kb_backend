<?php

declare(strict_types=1);

namespace App\Entity\Lead;

use App\Controller\Admin\Lead\DTO\Request\Order\Product\OrderVariantReqDto;
use App\Controller\Admin\Lead\DTO\Request\Order\Promotion\OrderPromotionReqDto;
use App\Controller\Admin\Lead\DTO\Request\Order\Shipping\OrderShippingReqDto;
use App\Doctrine\Types\OrderVariantType;
use App\Repository\Lead\OrderEntityRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderEntityRepository::class)]
class DealOrder
{
    #[Groups(['administrator'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['administrator'])]
    #[ORM\Column(type: OrderVariantType::NAME, nullable: true)]
    private array $productVariants = [];

    #[Groups(['administrator'])]
    #[ORM\Column(type: 'json', nullable: true)]
    private array $shipping = [];

    #[Groups(['administrator'])]
    #[ORM\Column(type: 'json', nullable: true)]
    private array $promotions = [];

    #[ORM\Column]
    private ?int $totalAmount = null;

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

    public function getTotalAmount(): ?int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function markAsUpdated(): static
    {
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }

    public function getProductVariants(): array
    {
        return $this->productVariants;
    }

    public function setProductVariants(array $productVariants): static
    {
        $this->productVariants = $productVariants;

        return $this;
    }

    // todo почему тут используется dto request-а?
    public function addProductVariant(OrderVariantReqDto $variantReqDto): static
    {
        $this->productVariants[] = $variantReqDto;

        return $this;
    }

    public function getShipping(): array
    {
        return $this->shipping;
    }

    public function setShipping(array $shipping): static
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function addShipping(OrderShippingReqDto $shipping): static
    {
        $this->shipping[] = $shipping;

        return $this;
    }

    public function getPromotions(): array
    {
        return $this->promotions;
    }

    public function setPromotions(array $promotions): static
    {
        $this->promotions = $promotions;

        return $this;
    }

    public function addPromotion(OrderPromotionReqDto $promotion): static
    {
        $this->promotions[] = $promotion;

        return $this;
    }
}

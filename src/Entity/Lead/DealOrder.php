<?php

declare(strict_types=1);

namespace App\Entity\Lead;

use App\Entity\Ecommerce\Product;
use App\Entity\Ecommerce\ProductVariant;
use App\Entity\Ecommerce\Promotion;
use App\Entity\Ecommerce\Shipping;
use App\Repository\Lead\OrderEntityRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    #[ORM\ManyToMany(targetEntity: ProductVariant::class, cascade: ['persist'])]
    private Collection $productVariants;

    #[Groups(['administrator'])]
    #[ORM\ManyToMany(targetEntity: Shipping::class, cascade: ['persist'])]
    private Collection $shipping;

    #[Groups(['administrator'])]
    #[ORM\ManyToMany(targetEntity: Promotion::class, cascade: ['persist'])]
    private Collection $promotions;

    #[ORM\Column]
    private ?int $totalAmount = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        if ($this->createdAt === null){
            $this->createdAt = new DateTimeImmutable();
        }

        $this->productVariants = new ArrayCollection();
        $this->promotions = new ArrayCollection();
        $this->shipping = new ArrayCollection();
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

    public function getProductVariants(): Collection
    {
        return $this->productVariants;
    }

    public function setProductVariants(Collection $productVariants): static
    {
        $this->productVariants = $productVariants;

        return $this;
    }

    public function addProductVariant(ProductVariant $product): static
    {
        if (!$this->productVariants->contains($product)) {
            $this->productVariants[] = $product;
        }

        return $this;
    }

    public function removeProductVariant(ProductVariant $product): static
    {
        $this->productVariants->removeElement($product);

        return $this;
    }

    public function getShipping(): Collection
    {
        return $this->shipping;
    }

    public function setShipping(Collection $shipping): static
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function addShipping(Shipping $shipping): static
    {
        if (!$this->shipping->contains($shipping)) {
            $this->shipping[] = $shipping;
        }

        return $this;
    }

    public function removeShipping(Shipping $shipping): static
    {
        $this->shipping->removeElement($shipping);

        return $this;
    }

    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function setPromotions(Collection $promotions): static
    {
        $this->promotions = $promotions;

        return $this;
    }

    public function addPromotion(Promotion $promotion): static
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions[] = $promotion;
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): static
    {
        $this->promotions->removeElement($promotion);

        return $this;
    }
}

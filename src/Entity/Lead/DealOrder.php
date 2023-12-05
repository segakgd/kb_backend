<?php

namespace App\Entity\Lead;

use App\Dto\deprecated\Ecommerce\ProductDto;
use App\Dto\deprecated\Ecommerce\PromotionDto;
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
    #[ORM\Column(nullable: true)]
    private array $products = [];

    #[Groups(['administrator'])]
    #[ORM\Column(nullable: true)]
    private array $shipping = [];

    #[Groups(['administrator'])]
    #[ORM\Column(nullable: true)]
    private array $promotions = [];

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
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function setProducts(?array $products): static
    {
        $this->products = $products;

        return $this;
    }

    public function addProduct(?ProductDto $product): static
    {
        $this->products[] = $product;

        return $this;
    }

    public function getShipping(): array
    {
        return $this->shipping;
    }

    public function setShipping(?array $shipping): static
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function getPromotions(): array
    {
        return $this->promotions;
    }

    public function setPromotions(?array $promotions): static
    {
        $this->promotions = $promotions;

        return $this;
    }

    public function addPromotion(PromotionDto $promotions): static
    {
        $this->promotions[] = $promotions;

        return $this;
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
}

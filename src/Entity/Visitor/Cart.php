<?php

namespace App\Entity\Visitor;

use App\Dto\deprecated\Ecommerce\ContactsDto;
use App\Dto\deprecated\Ecommerce\ProductDto;
use App\Dto\deprecated\Ecommerce\PromotionDto;
use App\Dto\deprecated\Ecommerce\ShippingDto;
use App\Repository\Visitor\CartRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $products = [];

    #[ORM\Column]
    private array $contacts = [];

    #[ORM\Column]
    private array $fields = [];

    #[ORM\Column]
    private array $shipping = []; // научить работать ShippingDto

    #[ORM\Column]
    private array $promotion = [];

    #[ORM\Column]
    private ?int $totalAmount = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    private ?int $visitorId = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

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

    public function setProducts(array $products): static
    {
        $this->products = $products;

        return $this;
    }

    public function addProduct(ProductDto $product): static
    {
        $this->products[] = $product;

        return $this;
    }

    public function getContacts(): array
    {
        return $this->contacts;
    }

    public function setContacts(array $contacts): static
    {
        $this->contacts = $contacts;

        return $this;
    }

    public function addContacts(ContactsDto $contact): static
    {
        $this->contacts[] = $contact;

        return $this;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function setFields(array $fields): static
    {
        $this->fields = $fields;

        return $this;
    }

    public function addFields(ContactsDto $field): static
    {
        $this->fields[] = $field;

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

    public function addShipping(ShippingDto $shipping): static
    {
        $this->shipping[] = $shipping;

        return $this;
    }

    public function getPromotion(): array
    {
        return $this->promotion;
    }

    public function setPromotion(array $promotion): static
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function addPromotion(PromotionDto $promotion): static
    {
        $this->promotion[] = $promotion;

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

    public function getVisitorId(): ?int
    {
        return $this->visitorId;
    }

    public function setVisitorId(?int $visitorId): static
    {
        $this->visitorId = $visitorId;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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
}

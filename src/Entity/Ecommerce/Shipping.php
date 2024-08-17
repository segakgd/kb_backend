<?php

declare(strict_types=1);

namespace App\Entity\Ecommerce;

use App\Controller\Admin\Shipping\Request\ShippingFieldRequest;
use App\Doctrine\Types\Shipping\ShippingFieldReqDtoArrayType;
use App\Doctrine\Types\Shipping\ShippingPriceType;
use App\Dto\Ecommerce\Shipping\ShippingPriceDto;
use App\Repository\Ecommerce\ShippingEntityRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ShippingEntityRepository::class)]
class Shipping
{
    #[Groups(['administrator'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['administrator'])]
    #[ORM\Column(length: 100, nullable: false)]
    private string $title;

    #[ORM\Column(length: 255, nullable: false)]
    private string $description;

    #[Groups(['administrator'])]
    #[ORM\Column(length: 20, nullable: false)]
    private string $type;

    #[ORM\Column(length: 50, nullable: false)]
    private string $calculationType;

    #[ORM\Column(nullable: false)]
    private int $projectId;

    #[Groups(['administrator'])]
    #[ORM\Column(type: ShippingPriceType::SHIPPING_PRICE_TYPE, nullable: true)]
    private ?ShippingPriceDto $price;

    #[ORM\Column(type: Types::BOOLEAN, nullable: false)]
    private bool $active;

    #[ORM\Column(nullable: true)]
    private ?int $applyFromAmount = null;
    #[ORM\Column(nullable: true)]
    private ?int $applyToAmount = null;

    #[ORM\Column(nullable: true)]
    private ?int $freeFrom = null;

    #[ORM\Column(nullable: false)]
    private bool $isNotFixed = false;

    #[ORM\Column(type: ShippingFieldReqDtoArrayType::SHIPPING_FIELD_REQ_DTO_ARRAY, nullable: false)]
    private array $fields = [];

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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function setProjectId(int $projectId): static
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getPrice(): ?ShippingPriceDto
    {
        return $this->price;
    }

    public function setPrice(?ShippingPriceDto $price): static
    {
        $this->price = $price;

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

    public function getCalculationType(): string
    {
        return $this->calculationType;
    }

    public function setCalculationType(string $calculationType): static
    {
        $this->calculationType = $calculationType;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }


    public function getApplyFromAmount(): ?int
    {
        return $this->applyFromAmount;
    }

    public function setApplyFromAmount(?int $applyFromAmount): static
    {
        $this->applyFromAmount = $applyFromAmount;

        return $this;
    }

    public function getApplyToAmount(): ?int
    {
        return $this->applyToAmount;
    }

    public function setApplyToAmount(?int $applyToAmount): static
    {
        $this->applyToAmount = $applyToAmount;

        return $this;
    }

    public function getFreeFrom(): ?int
    {
        return $this->freeFrom;
    }

    public function setFreeFrom(?int $freeFrom): static
    {
        $this->freeFrom = $freeFrom;

        return $this;
    }

    public function isNotFixed(): bool
    {
        return $this->isNotFixed;
    }

    public function setIsNotFixed(bool $isNotFixed): static
    {
        $this->isNotFixed = $isNotFixed;

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

    public function addField(ShippingFieldRequest $field): static
    {
        $this->fields[] = $field;

        return $this;
    }
}

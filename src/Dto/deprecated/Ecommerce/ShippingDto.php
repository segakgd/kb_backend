<?php

namespace App\Dto\deprecated\Ecommerce;

use Symfony\Component\Validator\Constraints as Assert;

class ShippingDto
{
    private ?int $id = null;

    private ?string $title = null;

    private ?PriceDto $price = null;

    #[Assert\Choice(['courier', 'pickup'])]
    private ?string $type = null;

    private ?int $projectId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): ?PriceDto
    {
        return $this->price;
    }

    public function setPrice(?PriceDto $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getProjectId(): ?int
    {
        return $this->projectId;
    }

    public function setProjectId(?int $projectId): self
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->getTitle(),
            'type' => $this->getType(),
            'price' => $this->getPrice(),
        ];
    }
}

<?php

namespace App\Dto\deprecated\Ecommerce;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PromotionDto
{
    private ?int $id = null;

    private ?string $name = null;

    private ?int $projectId = null;

    #[Assert\Choice(['code', 'discount'])]
    private ?string $type = null;

    private ?PriceDto $price = null;

    private ?int $amount = null;

    private ?string $code = null;

    private ?string $discountForm = null;

    private ?int $count = null;

    private ?bool $active = null;

    private ?DateTimeInterface $activeFrom = null;

    private ?DateTimeInterface $activeTo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function getPrice(): ?PriceDto
    {
        return $this->price;
    }

    public function setPrice(?PriceDto $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDiscountForm(): ?string
    {
        return $this->discountForm;
    }

    public function setDiscountForm(?string $discountForm): self
    {
        $this->discountForm = $discountForm;

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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getActiveFrom(): ?DateTimeInterface
    {
        return $this->activeFrom;
    }

    public function setActiveFrom(?DateTimeInterface $activeFrom): self
    {
        $this->activeFrom = $activeFrom;

        return $this;
    }

    public function getActiveTo(): ?DateTimeInterface
    {
        return $this->activeTo;
    }

    public function setActiveTo(?DateTimeInterface $activeTo): self
    {
        $this->activeTo = $activeTo;

        return $this;
    }
}

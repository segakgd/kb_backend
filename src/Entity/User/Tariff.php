<?php

namespace App\Entity\User;

use App\Repository\User\TariffRepository;
use App\Service\Common\Project\Enum\TariffCodeEnum;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TariffRepository::class)]
class Tariff
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 255)]
    private ?string $priceWF = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 100)]
    private ?string $code = null;

    #[ORM\Column]
    private ?bool $active = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPriceWF(): ?string
    {
        return $this->priceWF;
    }

    public function setPriceWF(string $priceWF): static
    {
        $this->priceWF = $priceWF;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCode(): ?TariffCodeEnum
    {
        return TariffCodeEnum::tryFrom($this->code);
    }

    public function setCode(TariffCodeEnum $code): static
    {
        $this->code = $code->value;

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
}

<?php

namespace App\Entity\User;

use App\Repository\User\ProjectSettingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectSettingRepository::class)]
class ProjectSetting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $projectId = null;

    #[ORM\Column]
    private ?int $tariffId = null;

    #[ORM\Column(type: Types::JSON)]
    private array $notification = [];

    #[ORM\Column(type: Types::JSON)]
    private array $basic = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectId(): ?int
    {
        return $this->projectId;
    }

    public function setProjectId(int $projectId): static
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getTariffId(): ?int
    {
        return $this->tariffId;
    }

    public function setTariffId(int $tariffId): static
    {
        $this->tariffId = $tariffId;

        return $this;
    }

    public function getNotification(): array
    {
        return $this->notification;
    }

    public function setNotification(array $notification): static
    {
        $this->notification = $notification;

        return $this;
    }

    public function getBasic(): array
    {
        return $this->basic;
    }

    public function setBasic(array $basic): static
    {
        $this->basic = $basic;

        return $this;
    }
}

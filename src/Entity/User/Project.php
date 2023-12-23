<?php

namespace App\Entity\User;

use App\Repository\User\ProjectEntityRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProjectEntityRepository::class)]
class Project
{
    public const STATUS_ACTIVE = 'active';

    public const STATUS_FROZEN = 'frozen';

    public const STATUS_BLOCKED = 'blocked';

    #[Groups(['administrator'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['administrator'])]
    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'projects')]
    private Collection $users;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $status = self::STATUS_ACTIVE;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $ActiveFrom = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $ActiveTo = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addProject($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeProject($this);
        }

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

    public function getActiveFrom(): ?DateTimeImmutable
    {
        return $this->ActiveFrom;
    }

    public function setActiveFrom(?DateTimeImmutable $ActiveFrom): static
    {
        $this->ActiveFrom = $ActiveFrom;

        return $this;
    }

    public function getActiveTo(): ?DateTimeImmutable
    {
        return $this->ActiveTo;
    }

    public function setActiveTo(?DateTimeImmutable $ActiveTo): static
    {
        $this->ActiveTo = $ActiveTo;

        return $this;
    }
}

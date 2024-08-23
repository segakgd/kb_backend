<?php

namespace App\Entity\User;

use App\Entity\User\Enum\ProjectStatusEnum;
use App\Repository\User\ProjectRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
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
    private ?string $status = ProjectStatusEnum::Active->value;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $activeTo = null;

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

    public function getStatus(): ?ProjectStatusEnum
    {
        return ProjectStatusEnum::tryFrom($this->status);
    }

    public function setStatus(ProjectStatusEnum $status): static
    {
        $this->status = $status->value;

        return $this;
    }

    public function getActiveTo(): ?DateTimeImmutable
    {
        return $this->activeTo;
    }

    public function setActiveTo(?DateTimeImmutable $activeTo): static
    {
        $this->activeTo = $activeTo;

        return $this;
    }
}

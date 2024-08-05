<?php

namespace App\Entity\User;

use App\Entity\Visitor\VisitorSession;
use App\Repository\User\BotRepository;
use App\Service\Common\Bot\Enum\BotTypeEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BotRepository::class)]
class Bot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\Column]
    private ?int $projectId = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $webhookUri = null;

    /**
     * @var Collection<int, VisitorSession>
     */
    #[ORM\OneToMany(mappedBy: 'bot', targetEntity: VisitorSession::class)]
    private Collection $visitorSessions;

    public function __construct()
    {
        $this->visitorSessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?BotTypeEnum
    {
        return BotTypeEnum::tryFrom($this->type);
    }

    public function setType(BotTypeEnum $type): static
    {
        $this->type = $type->value;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getWebhookUri(): ?string
    {
        return $this->webhookUri;
    }

    public function setWebhookUri(string $webhookUri): static
    {
        $this->webhookUri = $webhookUri;

        return $this;
    }

    /**
     * @return Collection<int, VisitorSession>
     */
    public function getVisitorSessions(): Collection
    {
        return $this->visitorSessions;
    }

    public function addVisitorSession(VisitorSession $visitorSession): static
    {
        if (!$this->visitorSessions->contains($visitorSession)) {
            $this->visitorSessions->add($visitorSession);
            $visitorSession->setBot($this);
        }

        return $this;
    }

    public function removeVisitorSession(VisitorSession $visitorSession): static
    {
        if ($this->visitorSessions->removeElement($visitorSession)) {
            // set the owning side to null (unless already changed)
            if ($visitorSession->getBot() === $this) {
                $visitorSession->setBot(null);
            }
        }

        return $this;
    }
}

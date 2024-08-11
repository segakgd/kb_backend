<?php

namespace App\Entity\Visitor;

use App\Entity\MessageHistory;
use App\Entity\SessionCache;
use App\Entity\User\Bot;
use App\Enum\Constructor\ChannelEnum;
use App\Repository\Visitor\VisitorSessionRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сессия пользователя бота
 */
#[ORM\Entity(repositoryClass: VisitorSessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 30)]
    private ?string $channel = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $projectId = null;

    #[ORM\Column]
    private ?int $chatId = null;

    #[ORM\ManyToOne(inversedBy: 'visitorSessions')]
    private ?Bot $bot = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?SessionCache $cache = null;

    /**
     * @var Collection<int, MessageHistory>
     */
    #[ORM\OneToMany(mappedBy: 'session', targetEntity: MessageHistory::class)]
    private Collection $messageHistory;

    public function __construct()
    {
        if ($this->createdAt === null) {
            $this->createdAt = new DateTimeImmutable();
        }
        $this->messageHistory = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getChannel(): ?ChannelEnum
    {
        return ChannelEnum::tryFrom($this->channel);
    }

    public function setChannel(ChannelEnum $channel): static
    {
        $this->channel = $channel->value;

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

    public function getProjectId(): ?int
    {
        return $this->projectId;
    }

    public function setProjectId(?int $projectId): static
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getChatId(): ?int
    {
        return $this->chatId;
    }

    public function setChatId(int $chatId): static
    {
        $this->chatId = $chatId;

        return $this;
    }

    public function getBot(): ?Bot
    {
        return $this->bot;
    }

    public function setBot(?Bot $bot): static
    {
        $this->bot = $bot;

        return $this;
    }

    public function getCache(): ?SessionCache
    {
        return $this->cache;
    }

    public function setCache(?SessionCache $cache): static
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @return Collection<int, MessageHistory>
     */
    public function getMessageHistory(): Collection
    {
        return $this->messageHistory;
    }

    public function addMessageHistory(MessageHistory $messageHistory): static
    {
        if (!$this->messageHistory->contains($messageHistory)) {
            $this->messageHistory->add($messageHistory);
            $messageHistory->setSession($this);
        }

        return $this;
    }

    public function removeMessageHistory(MessageHistory $messageHistory): static
    {
        if ($this->messageHistory->removeElement($messageHistory)) {
            // set the owning side to null (unless already changed)
            if ($messageHistory->getSession() === $this) {
                $messageHistory->setSession(null);
            }
        }

        return $this;
    }
}

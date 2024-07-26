<?php

namespace App\Entity\Visitor;

use App\Doctrine\Types\VisitorSessionCacheDtoArrayType;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\User\Bot;
use App\Repository\Visitor\VisitorSessionRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сессия пользователя бота
 */
#[ORM\Entity(repositoryClass: VisitorSessionRepository::class)]
class VisitorSession
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

    #[ORM\Column(type: VisitorSessionCacheDtoArrayType::TYPE_NAME)]
    private ?CacheDto $cache = null; // todo вынести в отдельную таблицу

    #[ORM\Column]
    private ?int $chatId = null;

    #[ORM\ManyToOne(inversedBy: 'visitorSessions')]
    private ?Bot $bot = null;

    public function __construct()
    {
        if ($this->createdAt === null) {
            $this->createdAt = new DateTimeImmutable();
        }
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

    public function getChannel(): ?string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): static
    {
        $this->channel = $channel;

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

    public function getCache(): CacheDto
    {
        return $this->cache;
    }

    public function setCache(CacheDto $cache): static
    {
        // clone потому что используем дто тип в ентити
        $this->cache = clone $cache;

        return $this;
    }

    public function getCacheContent(): ?string
    {
        return $this->cache['content'] ?? null;
    }

    public function getCacheStatusEvent(): ?string
    {
        return $this->cache['event']['status'] ?? null;
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
}

<?php

namespace App\Entity;

use App\Entity\Visitor\Session;
use App\Repository\MessageHistoryRepository;
use App\Service\Common\History\Enum\HistoryTypeEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageHistoryRepository::class)]
class MessageHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column]
    private array $keyBoard = [];

    #[ORM\Column]
    private array $images = [];

    #[ORM\Column(length: 20)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'messageHistory')]
    private ?Session $session;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getKeyBoard(): array
    {
        return $this->keyBoard;
    }

    public function setKeyBoard(array $keyBoard): static
    {
        $this->keyBoard = $keyBoard;

        return $this;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): static
    {
        $this->images = $images;

        return $this;
    }

    public function getType(): ?HistoryTypeEnum
    {
        return HistoryTypeEnum::from($this->type);
    }

    public function setType(HistoryTypeEnum $type): static
    {
        $this->type = $type->value;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): static
    {
        $this->session = $session;

        return $this;
    }
}

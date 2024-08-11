<?php

namespace App\Service\Constructor\Core\Dto;

use App\Doctrine\DoctrineMappingInterface;
use App\Dto\Responsible\ResponsibleMessageDto;

class Result implements ResultInterface, DoctrineMappingInterface
{
    private ?ResponsibleMessageDto $message = null;

    public function getMessage(): ?ResponsibleMessageDto
    {
        return $this->message;
    }

    public function setMessage(ResponsibleMessageDto $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function isEmptyMessage(): bool
    {
        return null === $this->message;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
        ];
    }

    public static function fromArray(array $data): static
    {
        $dto = new self();
        $dto->setMessage($data['message']);

        return $dto;
    }
}

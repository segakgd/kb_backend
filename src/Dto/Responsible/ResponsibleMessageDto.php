<?php

namespace App\Dto\Responsible;

use App\Doctrine\DoctrineMappingInterface;

class ResponsibleMessageDto implements DoctrineMappingInterface
{
    private string $message = '';

    private string $photo = '';

    private ?array $keyBoard = null;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getKeyBoard(): ?array
    {
        return $this->keyBoard;
    }

    public function setKeyBoard(?array $keyBoard): static
    {
        $this->keyBoard = $keyBoard;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'message'  => $this->message,
            'photo'    => $this->photo,
            'keyBoard' => $this->keyBoard,
        ];
    }

    public static function fromArray(array $data): static
    {
        $dto = new self();
        $dto->setMessage($data['message']);
        $dto->setPhoto($data['photo']);
        $dto->setKeyBoard($data['keyBoard']);

        return $dto;
    }
}

<?php

namespace App\Controller\Admin\History\DTO\Response;

use DateTimeImmutable;

class HistoryRespDto
{
    private DateTimeImmutable $createdAt;

    private string $type; // newLead, sendingMessage, login

    private string $status; // error, success, process

    private string $sender; // telegram, vk

    private string $recipient; // кому отправили (получатель)

    private HistoryErrorRespDto $error;

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function setSender(string $sender): void
    {
        $this->sender = $sender;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function setRecipient(string $recipient): void
    {
        $this->recipient = $recipient;
    }

    public function getError(): HistoryErrorRespDto
    {
        return $this->error;
    }

    public function setError(HistoryErrorRespDto $error): void
    {
        $this->error = $error;
    }
}

<?php

namespace App\Controller\Admin\History\DTO\Response;

use DateTimeImmutable;

class HistoryRespDto
{
    public const HISTORY_STATUS_ERROR = 'error'; // todo потом вынести из dto

    public const HISTORY_STATUS_SUCCESS = 'success'; // todo потом вынести из dto

    public const HISTORY_STATUS_PROCESS = 'process'; // todo потом вынести из dto

    public const HISTORY_TYPE_LEAD_NEW = 'newLead'; // todo потом вынести из dto

    public const HISTORY_TYPE_MESSAGE_SENDING = 'sendingMessage'; // todo потом вынести из dto

    public const HISTORY_TYPE_LOGIN = 'login'; // todo потом вынести из dto

    public const SENDER_TELEGRAM = 'telegram'; // todo потом вынести из dto

    public const SENDER_VK = 'vk'; // todo потом вынести из dto

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

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function setSender(string $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function setRecipient(string $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getError(): HistoryErrorRespDto
    {
        return $this->error;
    }

    public function setError(HistoryErrorRespDto $error): self
    {
        $this->error = $error;

        return $this;
    }
}

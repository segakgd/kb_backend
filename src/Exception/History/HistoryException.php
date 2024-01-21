<?php

namespace App\Exception\History;

use App\Controller\Admin\History\DTO\Response\HistoryErrorRespDto;
use App\Service\Admin\History\HistoryService;
use Exception;

class HistoryException extends Exception implements HistoryExceptionInterface
{
    public function __construct(
        private readonly int $projectId,
        private readonly string $type,
        private readonly HistoryErrorRespDto $error,
        private readonly ?string $sender = null,
        private readonly ?string $recipient = null,
    ) {
        parent::__construct();
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getStatus(): string
    {
        return HistoryService::HISTORY_STATUS_ERROR;
    }

    public function getSender(): ?string
    {
        return $this->sender;
    }

    public function getRecipient(): ?string
    {
        return $this->recipient;
    }

    public function getError(): HistoryErrorRespDto
    {
        return $this->error;
    }
}

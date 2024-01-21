<?php

namespace App\Exception\History;

use App\Controller\Admin\History\DTO\Response\HistoryErrorRespDto;

interface HistoryExceptionInterface
{
    public function getProjectId(): int;

    public function getType(): string;

    public function getStatus(): string;

    public function getSender(): ?string;

    public function getRecipient(): ?string;

    public function getError(): HistoryErrorRespDto;
}

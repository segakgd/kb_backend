<?php

namespace App\Service\Admin\History;

use App\Controller\Admin\History\DTO\Response\HistoryErrorRespDto;
use App\Entity\History\History;
use DateTimeImmutable;

interface HistoryServiceInterface
{
    public function findAll(int $projectId): array;

    public function add(
        int $projectId,
        string $type,
        string $status,
        string $sender,
        string $recipient,
        HistoryErrorRespDto $error,
        DateTimeImmutable $createdAt
    ): History;
}
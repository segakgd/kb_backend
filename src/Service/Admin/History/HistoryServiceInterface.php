<?php

namespace App\Service\Admin\History;

use App\Controller\Admin\History\DTO\Response\HistoryErrorRespDto;
use App\Entity\History\History;

interface HistoryServiceInterface
{
    public function findAll(int $projectId): array;

    public function add(
        int $projectId,
        string $type,
        string $status,
        ?string $sender = null,
        ?string $recipient = null,
        ?HistoryErrorRespDto $error = null,
    ): History;
}

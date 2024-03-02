<?php

namespace App\Service\Visitor\Session;

use App\Entity\Visitor\VisitorSession;

interface VisitorSessionServiceInterface
{
    public function findAll(int $projectId): array;

    public function identifyByChannel(int $chatId, int $botId, string $channel): ?VisitorSession;

    public function createVisitorSession(
        string $visitorName,
        int $chatId,
        int $botId,
        string $chanel,
        int $projectId,
    ): VisitorSession;

    public function rewriteVisitorEvent(VisitorSession $visitorSession, int $visitorEventId): void;
}

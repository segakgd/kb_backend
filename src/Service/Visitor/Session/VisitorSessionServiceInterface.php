<?php

namespace App\Service\Visitor\Session;

use App\Entity\Visitor\Visitor;
use App\Entity\Visitor\VisitorSession;

interface VisitorSessionServiceInterface
{
    public function getOrCreateSession(Visitor $visitor): ?VisitorSession;

    public function rewriteChatEvent(VisitorSession $visitorSession, int $visitorEventId): void;
}

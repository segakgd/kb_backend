<?php

namespace App\Service\Visitor\Session;

use App\Entity\Visitor\Visitor;
use App\Entity\Visitor\VisitorSession;
use App\Repository\Visitor\VisitorSessionRepository;
use DateTimeImmutable;

interface VisitorSessionServiceInterface
{
    public function getOrCreateSession(Visitor $visitor): ?VisitorSession;
}

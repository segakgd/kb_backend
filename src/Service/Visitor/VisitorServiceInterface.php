<?php

namespace App\Service\Visitor;

interface VisitorServiceInterface
{
    public function identifyUser(int $chatId, string $channel);
}

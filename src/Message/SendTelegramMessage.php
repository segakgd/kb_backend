<?php

namespace App\Message;

use App\Entity\Visitor\Session;
use App\Service\Constructor\Core\Dto\BotDto;
use App\Service\Constructor\Core\Dto\ResultInterface;

final readonly class SendTelegramMessage
{
    public function __construct(
        private Session $session,
        private ResultInterface $result,
        private BotDto $botDto,
    ) {}

    public function getSession(): Session
    {
        return $this->session;
    }

    public function getBotDto(): BotDto
    {
        return $this->botDto;
    }

    public function getResult(): ResultInterface
    {
        return $this->result;
    }
}

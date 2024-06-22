<?php

namespace App\Message;

use App\Service\Constructor\Core\Dto\BotDto;
use App\Service\Constructor\Core\Dto\ResultInterface;

final readonly class SendTelegramMessage
{
    public function __construct(
        private ResultInterface $result,
        private BotDto $botDto,
    ) {}

    public function getBotDto(): BotDto
    {
        return $this->botDto;
    }

    public function getResult(): ResultInterface
    {
        return $this->result;
    }
}

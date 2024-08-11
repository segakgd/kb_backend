<?php

namespace App\Service\Common\Sender\Channels;

use App\Dto\Responsible\ResponsibleMessageDto;
use App\Service\Integration\Telegram\TelegramService;
use Exception;

readonly class TelegramChannel
{
    public function __construct(
        private TelegramService $telegramService,
    ) {}

    /**
     * @throws Exception
     */
    public function send(ResponsibleMessageDto $message, string $token, int $chatId): void
    {
        if ($message->getPhoto()) {
            $this->telegramService->sendPhoto(
                responsibleMessageDto: $message,
                token: $token,
                chatId: $chatId
            );

            return;
        }

        if ($message->getMessage()) {
            $this->telegramService->sendMessage(
                responsibleMessageDto: $message,
                token: $token,
                chatId: $chatId
            );

            return;
        }

        throw new Exception('not found message');
    }
}

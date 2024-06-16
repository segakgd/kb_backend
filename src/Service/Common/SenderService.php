<?php

namespace App\Service\Common;

use App\Dto\Responsible\ResponsibleMessageDto;
use App\Service\Constructor\Core\Dto\Responsible;
use App\Service\Integration\Telegram\TelegramService;
use Exception;

readonly class SenderService
{
    public function __construct(
        private TelegramService       $telegramService,
        private MessageHistoryService $messageHistoryService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function sendMessages(Responsible $responsible): void
    {
        $message = $responsible->getResult()->getMessage();
        $token = $responsible->getBotDto()->getToken();
        $chatId = $responsible->getBotDto()->getChatId();

        if (isset($_SERVER['APP_ENV']) && $_SERVER['APP_ENV'] === 'prod') {
            $this->sendProd($message, $token, $chatId);
        }

        $this->sendDev($message);
    }

    /**
     * @throws Exception
     */
    private function sendProd(ResponsibleMessageDto $message, string $token, int $chatId): void
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
        } else {
            throw new Exception('not found message');
        }
    }

    private function sendDev(ResponsibleMessageDto $message): void
    {
        $this->messageHistoryService->create(
            message: $message->getMessage(),
            type: MessageHistoryService::INCOMING,
            keyboard: $message->getKeyBoard() ?? [],
            images: [
                [
                    'uri' => $message->getPhoto()
                ]
            ]
        );
    }
}

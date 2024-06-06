<?php

namespace App\Service\System\Common;

use App\Dto\Responsible\ResponsibleMessageDto;
use App\Service\Integration\Telegram\TelegramService;
use App\Service\System\Core\Dto\Responsible;
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
        $messages = $responsible->getResult()->getMessages();
        $token = $responsible->getBotDto()->getToken();
        $chatId = $responsible->getBotDto()->getChatId();

        /** @var ResponsibleMessageDto $message */
        foreach ($messages as $message) {
            if (isset($_SERVER['APP_ENV']) && $_SERVER['APP_ENV'] === 'prod') {
                $this->sendProd($message, $token, $chatId);
            }

            $this->sendDev($message);
        }
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

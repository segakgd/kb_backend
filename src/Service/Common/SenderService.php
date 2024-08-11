<?php

namespace App\Service\Common;

use App\Dto\Responsible\ResponsibleMessageDto;
use App\Service\Common\History\Enum\HistoryTypeEnum;
use App\Service\Common\History\MessageHistoryService;
use App\Service\Constructor\Core\Dto\BotDto;
use App\Service\Constructor\Core\Dto\ResultInterface;
use App\Service\Integration\Telegram\TelegramService;
use Exception;

readonly class SenderService
{
    public function __construct(
        private TelegramService $telegramService,
        private MessageHistoryService $messageHistoryService,
    ) {}

    /**
     * @throws Exception
     */
    public function sendMessages(
        ResultInterface $result,
        BotDto $botDto,
    ): void {
        $message = $result->getMessage();
        $token = $botDto->getToken();
        $chatId = $botDto->getChatId();

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
            type: HistoryTypeEnum::Outgoing,
            keyboard: $message->getKeyBoard() ?? [],
            images: [
                [
                    'uri' => $message->getPhoto(),
                ],
            ]
        );
    }
}

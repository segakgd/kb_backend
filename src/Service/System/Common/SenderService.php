<?php

namespace App\Service\System\Common;

use App\Dto\Contract\ContractMessageDto;
use App\Service\Integration\Telegram\TelegramService;
use App\Service\System\Resolver\Dto\Contract;
use Exception;

class SenderService
{
    public function __construct(
        private readonly TelegramService $telegramService,
        private readonly MessageHistoryService $messageHistoryService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function sendMessages(Contract $contract): void
    {
        $messages = $contract->getResult()->getMessages();
        $token = $contract->getBotDto()->getToken();
        $chatId = $contract->getBotDto()->getChatId();

        /** @var ContractMessageDto $message */
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
    private function sendProd(ContractMessageDto $message, string $token, int $chatId): void
    {
        if ($message->getPhoto()) {
            $this->telegramService->sendPhoto(
                contractMessageDto: $message,
                token: $token,
                chatId: $chatId
            );

            return;
        }

        if ($message->getMessage()) {
            $this->telegramService->sendMessage(
                contractMessageDto: $message,
                token: $token,
                chatId: $chatId
            );
        } else {
            throw new Exception('not found message');
        }
    }

    private function sendDev(ContractMessageDto $message): void
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

<?php

namespace App\Service\System\Common;

use App\Dto\Contract\ContractMessageDto;
use App\Entity\Visitor\VisitorSession;
use App\Service\Integration\Telegram\TelegramService;
use App\Service\System\Contract;
use App\Service\System\MessageHistoryService;
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
    public function sendMessages(Contract $contract, string $token, VisitorSession $visitorSession): void
    {
        $messages = $contract->getMessages();

        /** @var ContractMessageDto $message */
        foreach ($messages as $message) {

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

            if ($message->getPhoto()) {
                $this->telegramService->sendPhoto(
                    $message,
                    $token,
                    $visitorSession->getChatId()
                );

                continue;
            }

            if ($message->getMessage()) {
                $this->telegramService->sendMessage(
                    $message,
                    $token,
                    $visitorSession->getChatId()
                );
            } else {
                throw new Exception('not found message');
            }
        }
    }
}

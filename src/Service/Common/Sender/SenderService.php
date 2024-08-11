<?php

namespace App\Service\Common\Sender;

use App\Dto\Responsible\ResponsibleMessageDto;
use App\Entity\Visitor\Session;
use App\Enum\Constructor\ChannelEnum;
use App\Service\Common\History\Enum\HistoryTypeEnum;
use App\Service\Common\History\MessageHistoryService;
use App\Service\Common\Sender\Channels\TelegramChannel;
use App\Service\Constructor\Core\Dto\BotDto;
use App\Service\Constructor\Core\Dto\ResultInterface;
use Exception;

readonly class SenderService
{
    public function __construct(
        private TelegramChannel $telegramSenderService,
        private MessageHistoryService $messageHistoryService,
    ) {}

    /**
     * @throws Exception
     */
    public function sendMessages(
        Session $session,
        ResultInterface $result,
        BotDto $botDto,
    ): void {
        $message = $result->getMessage();
        $token = $botDto->getToken();
        $chatId = $botDto->getChatId();

        $this->sendDev($session, $message);

        if (!isset($_SERVER['APP_ENV'])) {
            throw new Exception('Undefined app env.');
        }

        if ($_SERVER['APP_ENV'] !== 'prod') {
            return;
        }

        match ($session->getChannel()) {
            ChannelEnum::Telegram => $this->telegramSenderService->send($message, $token, $chatId),
            default               => throw new Exception('Undefined channel ' . $session->getChannel()->value),
        };
    }

    private function sendDev(Session $session, ResponsibleMessageDto $message): void
    {
        $this->messageHistoryService->create(
            session: $session,
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

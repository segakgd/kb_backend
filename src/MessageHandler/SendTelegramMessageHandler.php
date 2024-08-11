<?php

namespace App\MessageHandler;

use App\Message\SendTelegramMessage;
use App\Service\Common\Sender\SenderService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

#[AsMessageHandler]
final readonly class SendTelegramMessageHandler
{
    public function __construct(
        private SenderService   $senderService,
        private LoggerInterface $logger,
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(SendTelegramMessage $message): void
    {
        try {
            $this->senderService->sendMessages(
                session: $message->getSession(),
                result: $message->getResult(),
                botDto: $message->getBotDto()
            );
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), $exception->getTrace());

            throw $exception;
        }
    }
}

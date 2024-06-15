<?php

namespace App\Service\Common;

use App\Entity\MessageHistory;
use App\Repository\MessageHistoryRepository;

class MessageHistoryService
{
    const INCOMING = 'incoming';

    const OUTGOING = 'outgoing';

    public function __construct(private readonly MessageHistoryRepository $historyRepository)
    {
    }

    public function create(
        string $message,
        string $type,
        array $keyboard = [],
        array $images = [],
    ): MessageHistory {
        $messageHistory = (new MessageHistory)
            ->setMessage($message)
            ->setType($type)
            ->setKeyBoard($keyboard)
            ->setImages($images)
        ;

        $this->historyRepository->saveAndFlush($messageHistory);

        return $messageHistory;
    }
}

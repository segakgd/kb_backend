<?php

namespace App\Service\Common\History;

use App\Entity\MessageHistory;
use App\Repository\MessageHistoryRepository;
use App\Service\Common\History\Enum\HistoryTypeEnum;

readonly class MessageHistoryService
{
    public function __construct(private MessageHistoryRepository $historyRepository) {}

    public function create(
        string $message,
        HistoryTypeEnum $type,
        array $keyboard = [],
        array $images = [],
    ): MessageHistory {
        $messageHistory = (new MessageHistory())
            ->setMessage($message)
            ->setType($type)
            ->setKeyBoard($keyboard)
            ->setImages($images);

        $this->historyRepository->saveAndFlush($messageHistory);

        return $messageHistory;
    }
}

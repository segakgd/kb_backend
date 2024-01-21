<?php

namespace App\Service\Common\History;

use App\Service\Admin\History\HistoryService;
use App\Service\Admin\History\HistoryServiceInterface;

class HistoryEventService
{
    public function __construct(
        private readonly HistoryServiceInterface $historyService,
    ) {
    }

    public function newLeadEvent(int $projectId): void
    {
        $this->historyService->add(
            $projectId,
            HistoryService::HISTORY_TYPE_NEW_LEAD,
            HistoryService::HISTORY_STATUS_SUCCESS,
        );
    }

    public function loginEvent(int $projectId): void
    {
        $this->historyService->add(
            $projectId,
            HistoryService::HISTORY_TYPE_LOGIN,
            HistoryService::HISTORY_STATUS_SUCCESS,
        );
    }

    public function sendMessageToTelegramEvent(int $projectId): void
    {
        $this->historyService->add(
            $projectId,
            HistoryService::HISTORY_TYPE_SEND_MESSAGE_TO_TELEGRAM_CHANNEL,
            HistoryService::HISTORY_STATUS_SUCCESS,
        );
    }
}

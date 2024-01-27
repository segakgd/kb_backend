<?php

namespace App\Service\Common\History;

use App\Controller\Admin\History\DTO\Response\HistoryErrorRespDto;
use App\Service\Admin\History\HistoryService;
use App\Service\Admin\History\HistoryServiceInterface;

class HistoryEventService
{
    public function __construct(
        private readonly HistoryServiceInterface $historyService,
    ) {
    }

    public function errorSystem(
        string $message,
        int $projectId,
        string $type,
        ?string $sender = null,
        ?string $recipient = null,
    ): void {
        if (HistoryService::notExistType($type)){
            return;
        }

        if ($sender && HistoryService::notExistSender($sender)){
            return;
        }

        $historyErrorRespDto = (new HistoryErrorRespDto())
            ->setCode($codeError ?? 'DEFAULT_CODE_ERROR') // todo установить DEFAULT_CODE_ERROR
            ->addContext(
                [
                    'message' => $message,
                    'sender' => $sender,
                    'recipient' => $recipient,
                ]
            )
        ;

        $this->historyService->add(
            $projectId,
            $type,
            HistoryService::HISTORY_STATUS_ERROR,
            $sender,
            $recipient,
            $historyErrorRespDto,
        );
    }

    public function webhookSuccess(int $projectId): void
    {
        $this->historyService->add(
            $projectId,
            HistoryService::HISTORY_TYPE_WEBHOOK,
            HistoryService::HISTORY_STATUS_SUCCESS,
        );
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

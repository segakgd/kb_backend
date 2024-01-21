<?php

namespace App\Service\Common\History;

use App\Controller\Admin\History\DTO\Response\HistoryErrorRespDto;
use App\Exception\History\HistoryException;
use App\Exception\History\HistoryExceptionInterface;
use App\Service\Admin\History\HistoryService;
use Exception;

class HistoryErrorService // todo можно перевести в разряд хелперов
{
    /**
     * @throws Exception
     */
    public static function errorSystem(string $message, int $projectId, string $type, ?string $sender = null): void
    {
        if (HistoryService::notExistType($type)){
            throw new Exception('Неизвестный тип ' . $type);
        }

        if ($sender && HistoryService::notExistSender($sender)){
            throw new Exception('Неизвестный отправитель ' . $sender);
        }

        static::createException($message, $type, $projectId);
    }

    /**
     * @throws HistoryExceptionInterface
     */
    private static function createException(
        string $message,
        string $type,
        int $projectId,
        ?string $codeError = null,
        ?string $sender = null,
        ?string $recipient = null,
    ): void {
        $error = (new HistoryErrorRespDto())
            ->setCode($codeError ?? 'DEFAULT_CODE_ERROR') // todo установить DEFAULT_CODE_ERROR
            ->addContext(
                [
                    'message' => $message,
                    'sender' => $sender,
                    'recipient' => $recipient,
                ]
            )
        ;

        throw new HistoryException(
            $projectId,
            $type,
            $error,
        );
    }
}

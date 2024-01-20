<?php

namespace App\Service\Common\History;

use App\Controller\Admin\History\DTO\Response\HistoryErrorRespDto;
use App\Exception\History\HistoryException;
use App\Exception\History\HistoryExceptionInterface;
use Exception;

class HistoryErrorService // todo можно перевести в разряд хелперов
{
    public const HISTORY_TYPE_NEW_LEAD = 'new_lead';

    public const HISTORY_TYPE_SEND_MESSAGE_TO_CHANNEL = 'send_message_to_channel';

    public const HISTORY_TYPE_SEND_MESSAGE_TO_TELEGRAM_CHANNEL = 'send_message_to_telegram_channel';

    public const HISTORY_TYPE_LOGIN = 'login';

    public const HISTORY_TYPES = [
        self::HISTORY_TYPE_NEW_LEAD,
        self::HISTORY_TYPE_SEND_MESSAGE_TO_CHANNEL,
        self::HISTORY_TYPE_SEND_MESSAGE_TO_TELEGRAM_CHANNEL,
        self::HISTORY_TYPE_LOGIN,
    ];

    public const HISTORY_SENDER_TELEGRAM = 'telegram';

    public const HISTORY_SENDERS = [
        self::HISTORY_SENDER_TELEGRAM,
    ];

    /**
     * @throws Exception
     */
    public static function errorSystem(string $message, int $projectId, string $type, ?string $sender = null): void
    {
        if (static::notExistType($type)){
            throw new Exception('Неизвестный тип ' . $type);
        }

        if ($sender && static::notExistSender($sender)){
            throw new Exception('Неизвестный отправитель ' . $sender);
        }

        static::createException($message, $type, $projectId);
    }

    private static function notExistType(string $type): bool
    {
        return !in_array($type, self::HISTORY_TYPES);
    }

    private static function notExistSender(string $sender): bool
    {
        return !in_array($sender, self::HISTORY_SENDERS);
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

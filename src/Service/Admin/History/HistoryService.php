<?php


namespace App\Service\Admin\History;

use App\Controller\Admin\History\DTO\Response\HistoryErrorRespDto;
use App\Entity\History\History;
use App\Repository\History\HistoryRepository;
use DateTimeImmutable;
use Symfony\Component\Serializer\SerializerInterface;

class HistoryService implements HistoryServiceInterface
{
    public const HISTORY_STATUS_ERROR = 'error';

    public const HISTORY_STATUS_SUCCESS = 'success';

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

    public function __construct(
        private readonly HistoryRepository $historyRepositoryRepository,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function findAll(int $projectId): array
    {
        return $this->historyRepositoryRepository->findBy(
            [
                'projectId' => $projectId
            ]
        );
    }

    public function add(
        int $projectId,
        string $type,
        string $status,
        ?string $sender = null,
        ?string $recipient = null,
        ?HistoryErrorRespDto $error = null,
    ): History {
        $error = $this->serializer->normalize($error);

        $history = (new History())
            ->setProjectId($projectId)
            ->setType($type)
            ->setStatus($status)
            ->setSender($sender)
            ->setRecipient($recipient)
            ->setError($error)
            ->setCreatedAt(new DateTimeImmutable())
        ;

        $this->historyRepositoryRepository->saveAndFlush($history);

        return $history;
    }

    public static function notExistType(string $type): bool
    {
        return !in_array($type, self::HISTORY_TYPES);
    }

    public static function notExistSender(string $sender): bool
    {
        return !in_array($sender, self::HISTORY_SENDERS);
    }
}

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

    public const HISTORY_STATUS_PROCESS = 'process';

    public const HISTORY_TYPE_NEW_LEAD = 'new_lead';

    public const HISTORY_TYPE_SEND_MESSAGE_TO_CHANNEL = 'send_message_to_channel';

    public const HISTORY_TYPE_LOGIN = 'login';

    public const HISTORY_SENDER_TELEGRAM = 'telegram';

    public const HISTORY_SENDER_VK = 'vk';

    public const HISTORY_RECIPIENT_BITRIX = 'bitrix';

    public const HISTORY_RECIPIENT_FLEXBE = 'flexbe';

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
        string $sender,
        string $recipient,
        HistoryErrorRespDto $error,
        DateTimeImmutable $createdAt
    ): History {
        $error = $this->serializer->normalize($error);

        $history = (new History())
            ->setProjectId($projectId)
            ->setType($type)
            ->setStatus($status)
            ->setSender($sender)
            ->setRecipient($recipient)
            ->setError($error)
            ->setCreatedAt($createdAt)
        ;

        $this->historyRepositoryRepository->saveAndFlush($history);

        return $history;
    }
}
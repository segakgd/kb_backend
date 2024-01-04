<?php


namespace App\Service\Admin\History;

use App\Entity\History\History;
use App\Repository\History\HistoryRepository;
use DateTimeImmutable;

class HistoryService implements HistoryServiceInterface
{
    public function __construct(
        private readonly HistoryRepository $historyRepositoryRepository,
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

    public function add(int $projectId): History
    {
        $history = (new History())
            ->setProjectId($projectId)
            ->setType('error')
            ->setStatus('dsad')
            ->setSender('asdsad')
            ->setRecipient('dsadas')
            ->setError([])
            ->setCreatedAt(new DateTimeImmutable())
        ;

        $this->historyRepositoryRepository->saveAndFlush($history);

        return $history;
    }
}
<?php


namespace App\Service\Admin\History;

use App\Repository\History\HistoryRepository;

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
}
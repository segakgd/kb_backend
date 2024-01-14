<?php

namespace App\Service\Admin\Ecommerce\History;

use App\Repository\History\HistoryRepository;

class HistoryService implements HistoryServiceInterface
{
    public function __construct(
        private readonly HistoryRepository $historyRepositoryRepository,
    ) {
    }
    public function getAll(): array
    {
        return $this->historyRepositoryRepository->findAll();
    }
}

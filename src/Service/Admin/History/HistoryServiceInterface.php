<?php

namespace App\Service\Admin\History;

interface HistoryServiceInterface
{
        public function findAll(int $projectId): array;
}
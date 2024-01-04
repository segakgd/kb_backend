<?php

namespace App\Service\Admin\History;

use App\Entity\History\History;

interface HistoryServiceInterface
{
    public function findAll(int $projectId): array;

    public function add(int $projectId): History;

}
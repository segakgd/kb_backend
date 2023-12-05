<?php

namespace App\Service\Admin\Ecommerce\Deal;

use App\Dto\deprecated\Ecommerce\DealDto;
use App\Entity\Lead\Deal;

interface DealManagerInterface
{
    public function getAll(int $projectId): array;

    public function getOne(int $projectId, int $dealId): ?Deal;

    public function add(DealDto $dealDto, int $projectId): Deal;

    public function update(DealDto $dealDto, int $projectId, int $dealId): Deal;

    public function remove(int $projectId, int $dealId): bool;
}

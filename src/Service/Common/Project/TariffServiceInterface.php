<?php

namespace App\Service\Common\Project;

use App\Entity\User\Project;
use App\Entity\User\Tariff;

interface TariffServiceInterface
{
    public function getTariffById(int $tariffId): Tariff;

    public function getAllTariff(): array;

    public function applyTariff(Project $project, string $code): bool;
}

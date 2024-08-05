<?php

namespace App\Service\Common\Project;

use App\Entity\User\Project;
use App\Entity\User\Tariff;
use App\Service\Common\Project\Enum\TariffCodeEnum;

interface TariffServiceInterface
{
    public function getTariffById(int $tariffId): ?Tariff;

    public function getTariffByCode(TariffCodeEnum $code): ?Tariff;

    public function getAllTariff(): array;

    public function applyTariff(Project $project, string $code): bool;
}

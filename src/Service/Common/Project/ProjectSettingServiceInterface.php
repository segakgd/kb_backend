<?php

namespace App\Service\Common\Project;

use App\Entity\User\Tariff;

interface ProjectSettingServiceInterface
{
    public function updateTariff(int $projectId, Tariff $tariff): bool;
}

<?php

namespace App\Service\Common\Project;

use App\Entity\User\Project;

interface TariffServiceInterface
{
    public function applyTariff(Project $project, string $code): bool;
}

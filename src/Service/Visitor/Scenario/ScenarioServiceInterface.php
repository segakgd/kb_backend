<?php

namespace App\Service\Visitor\Scenario;

use App\Entity\Scenario\Scenario;

interface ScenarioServiceInterface
{
    public function createScenario(
        array $settingItem,
        string $name,
        int $projectId,
        string $groupType,
        ?int $ownerId = null,
    ): Scenario;
}

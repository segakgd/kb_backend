<?php

namespace App\Service\Visitor\Scenario;

use App\Entity\Scenario\Scenario;

interface ScenarioServiceInterface
{
    public function createScenario(
        array $settingItem,
        int $projectId,
        string $groupType,
        int $botId,
        ?int $ownerId = null,
    ): Scenario;

    public function markAsRemoveScenario(int $projectId, int $botId);
}

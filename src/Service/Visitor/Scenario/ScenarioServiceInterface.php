<?php

namespace App\Service\Visitor\Scenario;

use App\Entity\Scenario\Scenario;

interface ScenarioServiceInterface
{
    public function findScenarioByNameAndType(
        string $type,
        string $content,
    ): Scenario;

    public function createScenario(
        array $settingItem,
        int $projectId,
        int $botId,
        ?int $ownerId = null,
    ): Scenario;

    public function markAllAsRemoveScenario(int $projectId, int $botId);
}

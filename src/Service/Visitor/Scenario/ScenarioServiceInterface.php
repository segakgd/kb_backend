<?php

namespace App\Service\Visitor\Scenario;

use App\Entity\Scenario\Scenario;

interface ScenarioServiceInterface
{
    public function findScenarioByNameAndType(
        string $type,
        string $content,
    ): Scenario;

    public function markAllAsRemoveScenario(int $projectId, int $botId);
}

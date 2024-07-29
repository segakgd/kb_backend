<?php

namespace App\Service\Constructor\Visitor;

use App\Entity\Scenario\Scenario;
use App\Enum\TargetAliasEnum;
use App\Service\Constructor\Core\Jumps\JumpProvider;
use App\Service\Constructor\Visitor\Scenario\ScenarioService;
use Exception;

readonly class ScenarioManager
{
    public function __construct(
        private ScenarioService $scenarioService,
    ) {}

    /**
     * @throws Exception
     */
    public function getByUuidOrDefault(string $uuid): Scenario
    {
        $scenario = $this->scenarioService->findByUUID($uuid);

        if (null === $scenario) {
            $scenario = $this->scenarioService->findByAlias(TargetAliasEnum::Default);
        }

        if (null === $scenario) {
            throw new Exception('Нет сценария по умолчанию');
        }

        return $scenario;
    }

    /**
     * @throws Exception
     */
    public function getScenarioByNameAndType(string $type, string $content): Scenario
    {
        $scenario = $this->scenarioService->findScenarioByNameAndType($type, $content);

        $targetEnum = JumpProvider::getJumpFromNavigate($content);

        if (is_null($scenario) && !is_null($targetEnum)) {
            $scenario = $this->scenarioService->findScenarioByTarget($targetEnum);
        }

        if (is_null($scenario)) {
            $scenario = $this->scenarioService->findByAlias(TargetAliasEnum::Default);
        }

        if (is_null($scenario)) {
            throw new Exception('Нет сценария по умолчанию');
        }

        return $scenario;
    }
}

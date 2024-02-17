<?php

namespace App\Converter;

use App\Dto\Scenario\WrapperScenarioDto;
use App\Service\Visitor\Scenario\ScenarioServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Throwable;

class ScenarioConverter
{
    public function __construct(
        private readonly ScenarioServiceInterface $scenarioService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws Exception
     */
    public function convert(WrapperScenarioDto $scenario, int $projectId, int $botId, int $ownerId = null): array
    {
        $scenarios = [];

        try {
            $this->entityManager->beginTransaction();

            $this->scenarioService->markAllAsRemoveScenario($projectId, $botId);
            $this->scenarioService->generateDefaultScenario($projectId, $botId); // todo переделать дефолтную

            $scenarios = $this->convertToEntity($scenario->getScenarios(), $projectId, $botId, $ownerId);
        } catch (Throwable) {
            $this->entityManager->rollback();
        }

        return $scenarios;
    }

    public function convertToEntity(array $settings, int $projectId, int $botId, int $ownerId = null): array
    {
        $result = [];

        foreach ($settings as $settingItem) {
            $scenario = $this->scenarioService->createScenario(
                $settingItem,
                $projectId,
                $botId,
                $ownerId,
            );

            if (isset($settingItem['sub'])) {
                $resultSud = $this->convertToEntity($settingItem['sub'], $projectId, $botId, $scenario->getId());

                $result = array_merge($result, $resultSud);
            }
        }

        return $result;
    }
}

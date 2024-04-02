<?php

namespace App\Converter;

use App\Dto\Scenario\ScenarioDto;
use App\Dto\Scenario\WrapperScenarioDto;
use App\Entity\Scenario\Scenario;
use App\Service\Visitor\Scenario\ScenarioService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Throwable;

class ScenarioConverter
{
    public function __construct(
        private readonly ScenarioService $scenarioService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function convert(WrapperScenarioDto $scenario, int $projectId, int $botId): array
    {
        try {
            $this->entityManager->beginTransaction();

            $this->scenarioService->markAllAsRemoveScenario($projectId, $botId);
            $this->scenarioService->generateDefaultScenario($projectId, $botId);

            $scenarios = $this->convertToEntity($scenario->getScenarios(), $projectId, $botId);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Throwable $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }

        return $scenarios;
    }

    public function convertToEntity(array $scenarios, int $projectId, int $botId): array
    {
        $scenarioEntities = [];

        /** @var ScenarioDto $scenario */
        foreach ($scenarios as $scenario) {
            $scenarioEntity = (new Scenario())
                ->setUUID($scenario->getUUID())
                ->setAlias($scenario->getName())
                ->setName($scenario->getName())
                ->setType($scenario->getType())
                ->setBotId($botId)
                ->setProjectId($projectId);

            foreach ($scenario->getSteps() as $step) {
                $scenarioEntity->addStep($step);
            }

            $this->entityManager->persist($scenarioEntity);
            $this->entityManager->flush($scenarioEntity);

            $scenarioEntities[] = $scenarioEntity;
        }

        return $scenarioEntities;
    }
}

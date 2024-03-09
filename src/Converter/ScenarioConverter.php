<?php

namespace App\Converter;

use App\Dto\Scenario\ScenarioDto;
use App\Dto\Scenario\WrapperScenarioDto;
use App\Entity\Scenario\Scenario;
use App\Service\Visitor\Scenario\ScenarioServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;
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

        foreach ($scenarios as $scenario) {
            /** @var ScenarioDto $scenario */


            $scenarioEntity = (new Scenario())
                ->setUUID($scenario->getUUID())
                ->setName($scenario->getName())
                ->setType($scenario->getType())
                ->setBotId($botId)
                ->setProjectId($projectId)
            ;


            foreach ($scenario->getSteps() as $scenarioStep) {
                $scenarioEntity->addStep($scenarioStep);
            }

            $scenarioEntities[] = $scenarioEntity;

            $this->entityManager->persist($scenarioEntity);
            $this->entityManager->flush($scenarioEntity);
        }

        return $scenarioEntities;
    }
}

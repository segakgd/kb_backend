<?php

namespace App\Converter;

use App\Dto\Scenario\ScenarioDto;
use App\Dto\Scenario\ScenarioCollection;
use App\Entity\Scenario\Scenario;
use App\Service\Visitor\Scenario\ScenarioService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Throwable;

readonly class ScenarioConverter
{
    public function __construct(
        private ScenarioService $scenarioService,
        private EntityManagerInterface $entityManager,
    ) {}

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function convert(ScenarioCollection $scenario, int $projectId, int $botId): array
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
                ->setUUID($scenario->getUUID() ?? uuid_create())
                ->setAlias($scenario->getAlias() ?? $scenario->getName())
                ->setName($scenario->getName())
                ->setType($scenario->getType())
                ->setBotId($botId)
                ->setProjectId($projectId);

            $scenarioEntity->setContract($scenario->getContract());

            $this->entityManager->persist($scenarioEntity);
            $this->entityManager->flush($scenarioEntity);

            $scenarioEntities[] = $scenarioEntity;
        }

        return $scenarioEntities;
    }
}

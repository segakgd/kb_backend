<?php

namespace App\Converter;

use App\Dto\Scenario\ScenarioCollection;
use App\Dto\Scenario\ScenarioDto;
use App\Entity\Scenario\Scenario;
use App\Entity\User\Bot;
use App\Service\Constructor\Visitor\Scenario\ScenarioService;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

readonly class ScenarioConverter
{
    public function __construct(
        private ScenarioService $scenarioService,
        private EntityManagerInterface $entityManager,
    ) {}

    /**
     * @throws Throwable
     */
    public function convert(ScenarioCollection $scenario, Bot $bot): array
    {
        try {
            $this->entityManager->beginTransaction();

            $this->scenarioService->removeAllScenarioForBot($bot);
            $this->scenarioService->generateDefaultScenario($bot);

            $scenarios = $this->convertToEntity($scenario->getScenarios(), $bot);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Throwable $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }

        return $scenarios;
    }

    private function convertToEntity(array $scenarios, Bot $bot): array
    {
        $scenarioEntities = [];

        /** @var ScenarioDto $scenario */
        foreach ($scenarios as $scenario) {
            $scenarioEntity = (new Scenario())
                ->setUUID($scenario->getUUID() ?? uuid_create())
                ->setAlias($scenario->getAlias() ?? $scenario->getName())
                ->setName($scenario->getName())
                ->setType($scenario->getType())
                ->setBotId($bot->getId())
                ->setProjectId($bot->getProjectId());

            $scenarioEntity->setContract($scenario->getContract());

            $this->entityManager->persist($scenarioEntity);
            $this->entityManager->flush($scenarioEntity);

            $scenarioEntities[] = $scenarioEntity;
        }

        return $scenarioEntities;
    }
}

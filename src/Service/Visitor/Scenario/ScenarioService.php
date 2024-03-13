<?php

namespace App\Service\Visitor\Scenario;

use App\Dto\Scenario\ScenarioStepDto;
use App\Entity\Scenario\Scenario;
use App\Repository\Scenario\ScenarioRepository;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;

class ScenarioService
{
    const SCENARIO_DEFAULT = 'default';

    const SCENARIO_MAIN = 'main';

    public function __construct(
        private readonly ScenarioRepository $scenarioRepository,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function getAllByProjectId(string $projectId): array
    {
        return $this->scenarioRepository->findBy(
            [
                'projectId' => $projectId,
                'deletedAt' => null,
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function findScenarioByUUID(string $uuid): Scenario
    {
        $scenario = $this->scenarioRepository->findOneBy(
            [
                'UUID' => $uuid,
                'deletedAt' => null,
            ]
        );

        if (null === $scenario) {
            $scenario = $this->getDefaultScenario();
        }

        if (null === $scenario) {
            throw new Exception('Нет сценария по умолчанию');
        }

        return $scenario;
    }

    public function getDefaultScenario(): ?Scenario
    {
        return $this->scenarioRepository->findOneBy(
            [
                'name' => static::SCENARIO_DEFAULT,
                'deletedAt' => null,
            ]
        );
    }

    public function getMainScenario(): ?Scenario
    {
        return $this->scenarioRepository->findOneBy(
            [
                'name' => static::SCENARIO_MAIN,
                'deletedAt' => null,
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function findScenarioByNameAndType(string $type, string $content): Scenario
    {
        $scenario = $this->getScenarioByNameAndType($type, $content);

        if (null === $scenario) {
            $scenario = $this->getDefaultScenario();
        }

        if (null === $scenario) {
            throw new Exception('Нет сценария по умолчанию');
        }

        return $scenario;
    }

    public function getScenarioByNameAndType(string $type, string $name): ?Scenario
    {
        return $this->scenarioRepository->findOneBy(
            [
                'type' => $type,
                'name' => $name,
                'deletedAt' => null,
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function generateDefaultScenario(int $projectId, int $botId): Scenario
    {
        $step = (new ScenarioStepDto())
            ->setMessage('Не знаю что вам ответить');

        $scenarioEntity = (new Scenario())
            ->setUUID(uuid_create())
            ->setType('message')
            ->setAlias('default')
            ->setName('default')
            ->setProjectId($projectId)
            ->setBotId($botId)
            ->setSteps(
                [
                    $this->serializer->normalize($step)
                ]
            );

        $this->scenarioRepository->saveAndFlush($scenarioEntity);

        return $scenarioEntity;
    }

    public function markAllAsRemoveScenario(int $projectId, int $botId): void
    {
        $scenarios = $this->scenarioRepository->findBy(
            [
                'projectId' => $projectId,
                'botId' => $botId,
            ]
        );

        foreach ($scenarios as $scenario) {
            $scenario->markAtDeleted();

            $this->scenarioRepository->saveAndFlush($scenario);
        }
    }
}

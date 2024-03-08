<?php

namespace App\Service\Visitor\Scenario;

use App\Dto\Scenario\ScenarioStepDto;
use App\Entity\Scenario\Scenario;
use App\Repository\Scenario\ScenarioRepository;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;

class ScenarioService implements ScenarioServiceInterface
{
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
                'name' => 'default', // todo в константу
                'deletedAt' => null,
            ]
        );
    }

    public function getMainScenario(): ?Scenario
    {
        return $this->scenarioRepository->findOneBy(
            [
                'name' => 'main', // todo в константу
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
    public function generateDefaultScenario(int $projectId, int $botId): Scenario // todo использовать!!
    {
        $step = (new ScenarioStepDto())
            ->setMessage('Не знаю что вам ответить');

        $step = (new Scenario())
            ->setUUID(uuid_create())
            ->setType('message')
            ->setName('default')
            ->setProjectId($projectId)
            ->setBotId($botId)
            ->addStep(
                $this->serializer->normalize($step)
            );

        $this->scenarioRepository->saveAndFlush($step);

        return $step;
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

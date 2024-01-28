<?php

namespace App\Service\Visitor\Scenario;

use App\Entity\Scenario\Scenario;
use App\Repository\Scenario\ScenarioRepository;
use Exception;

class ScenarioService implements ScenarioServiceInterface
{
    public function __construct(
        private readonly ScenarioRepository $scenarioRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function getScenario(
        string $type,
        string $content,
        ?int $ownerBehaviorScenarioId = null,
    ): Scenario {
        $scenario = $this->getScenarioByNameAndType($type, $content);

        if (null === $scenario && $ownerBehaviorScenarioId) {
            $scenario = $this->getScenarioByOwnerId($ownerBehaviorScenarioId);
        }

        if (null === $scenario) {
            $scenario = $this->getDefaultScenario();
        }

        if (null === $scenario) {
            throw new Exception('Нет сценария по умолчанию');
        }

        return $scenario;
    }

    /**
     * @throws Exception
     */
    public function createScenario(
        array $settingItem, // todo не самое лучшее рещение использовать массив для $settingItem, но пока оставил так. (надо будет переделать)
        int $projectId,
        int $botId,
        ?int $ownerId = null,
    ): Scenario {
        if (!isset($settingItem['type'])){
            throw new Exception('Не передан type');
        }

        if (!isset($settingItem['name'])){
            throw new Exception('Не передан name');
        }

        if (!isset($settingItem['content'])){
            throw new Exception('Не передан content');
        }

        $step = (new Scenario())
            ->setType($settingItem['type'])
            ->setName($settingItem['name'])
            ->setContent($settingItem['content'])
            ->setActionAfter($settingItem['actionAfter'] ?? null)
            ->setProjectId($projectId)
            ->setBotId($botId)
        ;

        if ($ownerId){
            $step->setOwnerStepId($ownerId);
        }

        $this->scenarioRepository->saveAndFlush($step);

        return $step;
    }

    // todo нужно ещё учитывать bot id
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

    // todo нужно ещё учитывать bot id
    public function getDefaultScenario(): ?Scenario
    {
        return $this->scenarioRepository->findOneBy(
            [
                'name' => 'default', // в константу
                'deletedAt' => null,
            ]
        );
    }

    public function getScenarioByOwnerId(int $ownerBehaviorScenarioId): ?Scenario
    {
        return $this->scenarioRepository->findOneBy(
            [
                'ownerStepId' => $ownerBehaviorScenarioId,
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function generateDefaultScenario(int $projectId, int $botId): Scenario
    {
        return $this->createScenario(
            [
                "name" => "default",
                "type" => "message",
                "content" => [
                    "message"=>"Не знаю что вам ответить",
                ],
            ],
            $projectId,
            $botId,
        );
    }

    public function markAsRemoveScenario(int $projectId, int $botId): void
    {
        $scenarios = $this->scenarioRepository->findBy(
            [
                'projectId' => $projectId,
                'botId' => $botId,
            ]
        );

        foreach ($scenarios as $scenario){
            $scenario->markAtDeleted();

            $this->scenarioRepository->saveAndFlush($scenario);
        }
    }
}

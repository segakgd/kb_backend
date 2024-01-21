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
    public function createScenario(
        array $settingItem, // todo не самое лучшее рещение использовать массив для $settingItem, но пока оставил так. (надо будет переделать)
        int $projectId,
        string $groupType,
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
            ->setGroupType($groupType)
            ->setProjectId($projectId)
            ->setBotId($botId)
        ;

        if ($ownerId){
            $step->setOwnerStepId($ownerId);
        }

        $this->scenarioRepository->saveAndFlush($step);

        return $step;
    }

    public function markAsRemoveScenario(int $projectId, int $botId)
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

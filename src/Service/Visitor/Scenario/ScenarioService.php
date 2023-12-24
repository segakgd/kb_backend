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
    public function createScenario(array $settingItem, string $name, ?int $ownerId = null): Scenario // todo не самое лучшее рещение использовать массив для $settingItem, но пока оставил так. (надо будет переделать)
    {
        if (!isset($settingItem['type'])){
            throw new Exception('Не передан type');
        }

        if (!isset($settingItem['content'])){
            throw new Exception('Не передан content');
        }

        $step = (new Scenario())
            ->setType($settingItem['type'])
            ->setName($name)
            ->setContent($settingItem['content'])
            ->setActionAfter($settingItem['actionAfter'] ?? null)
        ;

        if ($ownerId){
            $step->setOwnerStepId($ownerId);
        }

        $this->scenarioRepository->saveAndFlush($step);

        return $step;
    }
}

<?php

namespace App\Service\Admin\Scenario;

use App\Entity\Scenario\Scenario;
use App\Repository\Scenario\ScenarioRepository;

class BehaviorScenarioService
{
    public function __construct(
        private ScenarioRepository $behaviorScenarioRepository,
    ) {
    }

    public function getScenarioByNameAndType(string $type, string $name): ?Scenario
    {
        return $this->behaviorScenarioRepository->findOneBy(
            [
                'type' => $type,
                'name' => $name,
            ]
        );
    }

    public function getScenarioByOwnerId(int $ownerBehaviorScenarioId): ?Scenario
    {
        return $this->behaviorScenarioRepository->findOneBy(
            [
                'ownerStepId' => $ownerBehaviorScenarioId,
            ]
        );
    }

    public function generateDefaultScenario(): Scenario
    {
        $behaviorScenario = (new Scenario)
            ->setName('default')
            ->setType('message')
            ->setContent(
                [
                    'message' => 'Не понимаю что вы хотите, выберите одну из доступных комманд',
                ]
            )
        ;

        $this->behaviorScenarioRepository->save($behaviorScenario);

        return $behaviorScenario;
    }
}

<?php

namespace App\Service\Admin\Scenario;

use App\Controller\Admin\Scenario\DTO\Request\ScenarioReqDto;
use App\Entity\Scenario\ScenarioTemplate;
use App\Repository\Scenario\ScenarioTemplateRepository;

class ScenarioTemplateService
{
    public function __construct(
        private readonly ScenarioTemplateRepository $scenarioTemplateRepository,
    ) {
    }

    public function getAllByProjectId(int $projectId): array
    {
        return $this->scenarioTemplateRepository->findBy(
            [
                'projectId' => $projectId,
            ]
        );
    }

    public function create(ScenarioReqDto $dto, int $projectId): ?ScenarioTemplate
    {
        $scenarioTemplate = (new ScenarioTemplate())
            ->setName($dto->getName())
            ->setScenario($dto->getScenario())
            ->setProjectId($projectId)
        ;

        $this->scenarioTemplateRepository->saveAndFlush($scenarioTemplate);

        return $scenarioTemplate;
    }
}

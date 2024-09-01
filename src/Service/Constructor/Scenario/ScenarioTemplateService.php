<?php

namespace App\Service\Constructor\Scenario;

use App\Entity\Scenario\ScenarioTemplate;
use App\Entity\User\Project;
use App\Repository\Scenario\ScenarioTemplateRepository;
use Exception;

readonly class ScenarioTemplateService
{
    public function __construct(
        private ScenarioTemplateRepository $scenarioTemplateRepository,
    ) {}

    /**
     * @throws Exception
     */
    public function all(Project $project): ?ScenarioTemplate
    {
        return $this->scenarioTemplateRepository->findOneBy(
            [
                'projectId' => $project,
            ]
        );
    }
}

<?php

namespace App\Service\Constructor\Scenario;

use App\Controller\Admin\ScenarioTemplate\Request\ScenarioTemplateSearchRequest;
use App\Entity\User\Project;
use App\Repository\Dto\PaginationCollection;
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
    public function search(Project $project, ScenarioTemplateSearchRequest $requestDto): PaginationCollection
    {
        return $this->scenarioTemplateRepository->findBy(
            [
                'projectId' => $project,
            ]
        );
    }
}

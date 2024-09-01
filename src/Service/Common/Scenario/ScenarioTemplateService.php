<?php

namespace App\Service\Common\Scenario;

use App\Controller\Admin\ScenarioTemplate\Request\ScenarioTemplateRequest;
use App\Controller\Admin\ScenarioTemplate\Request\ScenarioTemplateSearchRequest;
use App\Entity\Scenario\ScenarioTemplate;
use App\Entity\User\Project;
use App\Repository\Dto\PaginationCollection;
use App\Repository\Scenario\ScenarioTemplateRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

readonly class ScenarioTemplateService
{
    public function __construct(
        private ScenarioTemplateRepository $scenarioTemplateRepository,
    ) {}

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function search(Project $project, ScenarioTemplateSearchRequest $requestDto): PaginationCollection
    {
        return $this->scenarioTemplateRepository->search($project, $requestDto);
    }

    public function getAllByProjectId(int $projectId): array
    {
        return $this->scenarioTemplateRepository->findBy(
            [
                'projectId' => $projectId,
            ]
        );
    }

    public function create(ScenarioTemplateRequest $dto, int $projectId): ?ScenarioTemplate
    {
        $scenarioTemplate = (new ScenarioTemplate())
            ->setName($dto->getName())
            ->setProjectId($projectId);

        $this->scenarioTemplateRepository->saveAndFlush($scenarioTemplate);

        return $scenarioTemplate;
    }
}

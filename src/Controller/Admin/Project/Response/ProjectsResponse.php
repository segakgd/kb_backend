<?php

namespace App\Controller\Admin\Project\Response;

use App\Controller\Admin\Project\DTO\Response\ProjectRespDto;
use App\Controller\Admin\Project\DTO\Response\Statistic\ProjectStatisticsRespDto;
use App\Entity\User\Project;

class ProjectsResponse
{
    public function mapToResponse(array $projects, ProjectStatisticsRespDto $fakeStatisticsByProject): array
    {
        $result = [];

        /** @var Project $project */
        foreach ($projects as $project) {

            $result[] = (new ProjectRespDto())
                ->setId($project->getId())
                ->setName($project->getName())
                ->setStatus($project->getStatus())
                ->setStatistic($fakeStatisticsByProject)
                ->setActiveFrom($project->getActiveFrom())
                ->setActiveTo($project->getActiveTo());
        }

        return $result;
    }
}
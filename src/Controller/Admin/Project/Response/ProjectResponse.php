<?php

namespace App\Controller\Admin\Project\Response;

use App\Controller\Admin\Project\DTO\Response\ProjectRespDto;
use App\Controller\Admin\Project\DTO\Response\Statistic\ProjectStatisticsRespDto;
use App\Entity\User\Project;

class ProjectResponse
{
    public function mapToResponse(Project $project, ProjectStatisticsRespDto $fakeStatisticsByProject): ProjectRespDto
    {
        return (new ProjectRespDto())
            ->setId($project->getId())
            ->setName($project->getName())
            ->setStatus($project->getStatus())
            ->setStatistic($fakeStatisticsByProject)
            ->setActiveFrom($project->getActiveFrom())
            ->setActiveTo($project->getActiveTo());
    }
}
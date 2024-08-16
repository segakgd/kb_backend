<?php

namespace App\Controller\Admin\Project\Response;

use App\Controller\Admin\Project\DTO\Response\ProjectRespDto;
use App\Entity\User\Project;

class ProjectResponse
{
    public function mapToResponse(Project $project): ProjectRespDto
    {
        return (new ProjectRespDto())
            ->setId($project->getId())
            ->setName($project->getName())
            ->setStatus($project->getStatus())
            ->setActiveFrom($project->getActiveFrom())
            ->setActiveTo($project->getActiveTo());
    }
}

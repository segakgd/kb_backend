<?php

namespace App\Service\Common\Project;

use App\Controller\Admin\Project\Request\ProjectCreateRequest;
use App\Controller\Admin\Project\Request\ProjectSearchRequest;
use App\Controller\Admin\Project\Request\ProjectUpdateRequest;
use App\Entity\User\Project;
use App\Entity\User\User;
use App\Repository\Dto\PaginationCollection;

interface ProjectServiceInterface
{
    public function search(User $user, ProjectSearchRequest $projectSearchRequest): PaginationCollection;

    public function findOneById(int $projectId): Project;

    public function add(ProjectCreateRequest $projectDto, User $user): Project;

    public function update(ProjectUpdateRequest $projectUpdateReqDto, Project $project): Project;

    public function remove(int $projectId): bool;

    public function isExist(int $id): bool;
}

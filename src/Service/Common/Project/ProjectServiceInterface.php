<?php

namespace App\Service\Common\Project;

use App\Dto\Project\ProjectDto;
use App\Entity\User\Project;
use App\Entity\User\User;

interface ProjectServiceInterface
{
    public function getAll(User $user): array;

    public function getOne(int $projectId): ?Project;

    public function add(ProjectDto $projectDto, User $user): Project;

    public function update(ProjectDto $projectDto, int $projectId): Project;

    public function remove(int $projectId): bool;

    public function isExist(int $id): bool;
}

<?php

namespace App\Service\Common\Project;

use App\Controller\Admin\Project\Request\ProjectCreateRequest;
use App\Controller\Admin\Project\Request\ProjectUpdateRequest;
use App\Entity\User\Enum\ProjectStatusEnum;
use App\Entity\User\Project;
use App\Entity\User\User;
use App\Repository\Dto\PaginateCollection;
use App\Repository\User\ProjectRepository;
use Psr\Log\LoggerInterface;
use Throwable;

readonly class ProjectService implements ProjectServiceInterface
{
    public function __construct(
        private ProjectRepository $projectEntityRepository,
        private ProjectSettingServiceInterface $projectSettingService,
        private LoggerInterface $logger,
    ) {}

    public function search(User $user): PaginateCollection
    {
        return $this->projectEntityRepository->findByUser($user);
    }

    public function findOneById(int $projectId): Project
    {
        return $this->projectEntityRepository->find($projectId);
    }

    public function add(ProjectCreateRequest $projectDto, User $user): Project
    {
        $entity = (new Project());

        $entity->addUser($user);
        $entity->setName($projectDto->getName());

        $this->projectEntityRepository->saveAndFlush($entity);

        $this->projectSettingService->initSetting($entity->getId());

        return $entity;
    }

    public function update(ProjectUpdateRequest $projectUpdateReqDto, Project $project): Project
    {
        $project->setName($projectUpdateReqDto->getName());
        $project->setStatus(
            ProjectStatusEnum::tryFrom($projectUpdateReqDto->getStatus())
        );

        $this->projectEntityRepository->saveAndFlush($project);

        return $project;
    }

    public function remove(int $projectId): bool
    {
        try {
            $project = $this->projectEntityRepository->find($projectId);

            if ($project) {
                $this->projectEntityRepository->removeAndFlush($project);
            }
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());

            return false;
        }

        return true;
    }

    public function isExist(int $id): bool
    {
        return (bool) $this->projectEntityRepository->find($id);
    }
}

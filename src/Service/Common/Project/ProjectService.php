<?php

namespace App\Service\Common\Project;

use App\Controller\Admin\Project\DTO\Request\ProjectCreateReqDto;
use App\Entity\User\Project;
use App\Entity\User\User;
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

    public function findOneById(int $projectId): Project
    {
        return $this->projectEntityRepository->find($projectId);
    }

    public function getAll(User $user): array
    {
        return $this->projectEntityRepository->findByUser($user);
    }

    public function add(ProjectCreateReqDto $projectDto, User $user): Project
    {
        $entity = (new Project());

        $entity->addUser($user);
        $entity->setName($projectDto->getName());

        $this->projectEntityRepository->saveAndFlush($entity);

        $this->projectSettingService->initSetting($entity->getId());

        return $entity;
    }

    public function remove(int $projectId): bool
    {
        $project = $this->projectEntityRepository->find($projectId);

        try {
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

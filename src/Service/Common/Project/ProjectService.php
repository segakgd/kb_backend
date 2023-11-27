<?php

namespace App\Service\Common\Project;

use App\Dto\Project\ProjectDto;
use App\Entity\User\Project;
use App\Entity\User\User;
use App\Repository\User\ProjectEntityRepository;
use Psr\Log\LoggerInterface;
use Throwable;

class ProjectService implements ProjectServiceInterface
{
    public function __construct(
        private readonly ProjectEntityRepository $projectEntityRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function getAll(User $user): array
    {
        return $user->getProjects()->toArray(); // todo жёсткий костыль
    }

    public function getOne(int $projectId): ?Project
    {
        return $this->projectEntityRepository->find($projectId);
    }

    public function add(ProjectDto $projectDto, User $user): Project
    {
        $entity = (new Project);

        $entity->addUser($user);
        $entity
            ->setName($projectDto->getName())
        ;

        $this->projectEntityRepository->saveAndFlush($entity);

        return $entity;
    }

    public function update(ProjectDto $projectDto, int $projectId): Project
    {
        $entity = $this->getOne($projectId);
        $entity
            ->setName($projectDto->getName())
        ;

        $this->projectEntityRepository->saveAndFlush($entity);

        return $entity;
    }

    public function remove(int $projectId): bool
    {
        $project = $this->getOne($projectId);

        try {
            if ($project){
                $this->projectEntityRepository->removeAndFlush($project);
            }

        } catch (Throwable $exception){
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

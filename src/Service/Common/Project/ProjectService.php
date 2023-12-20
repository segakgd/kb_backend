<?php

namespace App\Service\Common\Project;

use App\Controller\Admin\Project\DTO\Request\ProjectCreateReqDto;
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

    public function add(ProjectCreateReqDto $projectDto, User $user): Project
    {
        $entity = (new Project);

        $entity->addUser($user);
        $entity->setName($projectDto->getName());
        $projectDto->getBot(); // todo это должно использоваться для чего?? Точно ли нужно?
        $projectDto->getMode(); // todo это должно влитять на те модули который хочет получить клиент. Но возможно как-то рано об этом задумались.

        $this->projectEntityRepository->saveAndFlush($entity);

        return $entity;
    }

    public function remove(int $projectId): bool
    {
        $project = $this->projectEntityRepository->find($projectId);

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

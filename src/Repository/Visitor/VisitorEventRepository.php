<?php

namespace App\Repository\Visitor;

use App\Entity\Visitor\VisitorEvent;
use App\Enum\VisitorEventStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VisitorEvent>
 *
 * @method VisitorEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method VisitorEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method VisitorEvent[]    findAll()
 * @method VisitorEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitorEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VisitorEvent::class);
    }

    public function findAllByProjectId(int $projectId): array
    {
        return $this->findBy(
            [
                'projectId' => $projectId
            ]
        );
    }

    public function getLastByProjectId(int $projectId): ?VisitorEvent
    {
        return $this->findOneBy(
            [
                'projectId' => $projectId
            ],
            [
                'createdAt' => 'desc'
            ]
        );
    }

    public function findOneById(int $id): ?VisitorEvent
    {
        return $this->find($id);
    }

    public function findOneByStatus(array $statuses): ?VisitorEvent
    {
        $statusesRequest = [];

        /** @var VisitorEventStatusEnum $status */
        foreach ($statuses as $status) {
            $statusesRequest[] = $status->value;
        }

        return $this->findOneBy(
            [
                'status' => $statusesRequest,
            ]
        );
    }

    public function updateChatEventStatus(VisitorEvent $chatEvent, VisitorEventStatusEnum $status): void
    {
        $chatEvent->setStatus($status);

        $this->saveAndFlush($chatEvent);
    }

    public function saveAndFlush(VisitorEvent $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function getVisitorEventIfExistByScenarioUUID(string $visitorEventUUID): ?VisitorEvent
    {
        return $this->findOneBy(
            [
                'scenarioUUID' => $visitorEventUUID,
                'status' => [
                    VisitorEventStatusEnum::New->value,
                    VisitorEventStatusEnum::Waiting->value
                ],
            ]
        );
    }

    public function removeById(int $visitorEventId): void
    {
        $visitorEvent = $this->find($visitorEventId);

        if ($visitorEvent) {
            $this->getEntityManager()->remove($visitorEvent);
            $this->getEntityManager()->flush();
        }
    }

    // todo нужен?
    public function remove(VisitorEvent $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}

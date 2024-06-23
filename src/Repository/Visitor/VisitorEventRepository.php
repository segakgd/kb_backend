<?php

namespace App\Repository\Visitor;

use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
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
                'projectId' => $projectId,
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

    public function getVisitorEventIfExist(VisitorSession $visitorSession): ?VisitorEvent
    {
        return $this->findOneBy(
            [
                'sessionId' => $visitorSession->getId(),
            ],
            [
                'id' => 'DESC',
            ],
        );
    }
}

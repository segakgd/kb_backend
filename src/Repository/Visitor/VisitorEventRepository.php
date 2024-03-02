<?php

namespace App\Repository\Visitor;

use App\Entity\Visitor\VisitorEvent;
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

    public function findOneById(int $id): ?VisitorEvent
    {
        return $this->find($id);
    }

    public function findOneByStatus(string $status): ?VisitorEvent
    {
        return $this->findOneBy(
            [
                'status' => $status,
            ]
        );
    }

    public function updateChatEventStatus(VisitorEvent $chatEvent, string $status): void
    {
        $chatEvent->setStatus($status);

        $this->saveAndFlush($chatEvent);
    }

    public function getVisitorEventIfExist(?int $visitorEventId): ?VisitorEvent
    {
        if (!$visitorEventId) {
            return null;
        }

        return $this->findOneBy(
            [
                'id' => $visitorEventId,
                'status' => ['new', 'await'],
            ]
        );
    }

    public function saveAndFlush(VisitorEvent $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(VisitorEvent $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    public function removeById(int $visitorEventId): void
    {
        $visitorEvent = $this->find($visitorEventId);

        if ($visitorEvent){
            $this->getEntityManager()->remove($visitorEvent);
            $this->getEntityManager()->flush();
        }
    }
}

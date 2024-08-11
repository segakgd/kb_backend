<?php

namespace App\Repository\Visitor;

use App\Entity\Visitor\Event;
use App\Entity\Visitor\Session;
use App\Enum\VisitorEventStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitorEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findAllByProjectId(int $projectId): array
    {
        return $this->findBy(
            [
                'projectId' => $projectId,
            ]
        );
    }

    public function updateChatEventStatus(Event $chatEvent, VisitorEventStatusEnum $status): void
    {
        $chatEvent->setStatus($status);

        $this->saveAndFlush($chatEvent);
    }

    public function saveAndFlush(Event $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function getVisitorEventIfExist(Session $visitorSession): ?Event
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

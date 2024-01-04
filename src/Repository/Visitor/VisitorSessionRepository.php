<?php

namespace App\Repository\Visitor;

use App\Entity\Visitor\VisitorSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VisitorSession>
 *
 * @method VisitorSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method VisitorSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method VisitorSession[]    findAll()
 * @method VisitorSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitorSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VisitorSession::class);
    }

    public function findByEventId(int $eventId): ?VisitorSession
    {
        return $this->findOneBy(
            [
                'chatEvent' => $eventId
            ]
        );
    }

    public function save(VisitorSession $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }
}

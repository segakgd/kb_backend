<?php

namespace App\Repository;

use App\Entity\MessageHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MessageHistory>
 *
 * @method MessageHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageHistory[]    findAll()
 * @method MessageHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageHistory::class);
    }

    public function saveAndFlush(MessageHistory $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function removeAndFlush(MessageHistory $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}

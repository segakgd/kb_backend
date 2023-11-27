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
}

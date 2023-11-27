<?php

namespace App\Repository\Lead;

use App\Entity\Lead\DealOrder;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DealOrder>
 *
 * @method DealOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method DealOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method DealOrder[]    findAll()
 * @method DealOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DealOrder::class);
    }

    public function saveAndFlush(DealOrder $entity): void
    {
        $entity->setUpdatedAt(new DateTimeImmutable());

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function removeAndFlush(DealOrder $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}

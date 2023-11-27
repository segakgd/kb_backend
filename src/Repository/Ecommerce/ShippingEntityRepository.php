<?php

namespace App\Repository\Ecommerce;

use App\Entity\Ecommerce\Shipping;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Shipping>
 *
 * @method Shipping|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shipping|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shipping[]    findAll()
 * @method Shipping[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShippingEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shipping::class);
    }

    public function saveAndFlush(Shipping $entity): void
    {
        $entity->setUpdatedAt(new DateTimeImmutable());

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }

    public function removeAndFlush(Shipping $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush($entity);
    }
}

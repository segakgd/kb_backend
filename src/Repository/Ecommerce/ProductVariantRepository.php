<?php

namespace App\Repository\Ecommerce;

use App\Entity\Ecommerce\ProductVariant;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductVariant>
 *
 * @method ProductVariant|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductVariant|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductVariant[]    findAll()
 * @method ProductVariant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductVariantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductVariant::class);
    }

    public function saveAndFlush(ProductVariant $entity): void
    {
        $entity->setUpdatedAt(new DateTimeImmutable());

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function removeAndFlush(ProductVariant $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}

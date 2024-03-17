<?php

namespace App\Repository\Ecommerce;

use App\Entity\Ecommerce\Product;
use App\Entity\Ecommerce\ProductCategory;
use App\Helper\CommonHelper;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function saveAndFlush(Product $entity): void
    {
        $entity->setUpdatedAt(new DateTimeImmutable());

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function removeAndFlush(Product $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}

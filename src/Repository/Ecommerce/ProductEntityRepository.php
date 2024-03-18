<?php

namespace App\Repository\Ecommerce;

use App\Entity\Ecommerce\Product;
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

    /**
     * @throws Exception
     */
    public function getPopularProducts($page = 1): array
    {
        $queryBuilder = $this->createQueryBuilder('product');
        $queryBuilder
            ->where($queryBuilder->expr()->in('product.id', [1, 2]))
            ->setFirstResult($page - 1)
            ->setMaxResults(1)
        ;

        $products = $queryBuilder->getQuery()->execute();

        $queryBuilder2 = $this->createQueryBuilder('product')
            ->where($queryBuilder->expr()->in('product.id', [1, 2]))
        ;
        $paginate = CommonHelper::buildPaginate($page, count($queryBuilder2->getQuery()->execute()));

        return [
            'items' => $products,
            'paginate' => $paginate,
        ];
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

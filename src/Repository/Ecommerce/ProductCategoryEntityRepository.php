<?php

namespace App\Repository\Ecommerce;

use App\Entity\Ecommerce\ProductCategory;
use App\Helper\CommonHelper;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<ProductCategory>
 *
 * @method ProductCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCategory[]    findAll()
 * @method ProductCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductCategoryEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCategory::class);
    }

    /**
     * @throws Exception
     */
    public function findProductsByCategory(int $categoryId, int $page = 1): array
    {
        $queryBuilder = $this->createQueryBuilder('pc')
            ->where('pc.id = :categoryId')
            ->setParameter('categoryId', $categoryId);

        $category = $queryBuilder->getQuery()->getOneOrNullResult();

        if (!$category) {
            throw new Exception('not found category');
        }

        /** @var ProductCategory $category */
        $products = $category->getProducts();

        $count = $products->count();

        if ($page < 1) {
            $page = $count;
        }

        if ($page > $count) {
            $page = 1;
        }

        $paginate = CommonHelper::buildPaginate($page, $products->count());

        return [
            'items' => [$products->get($page - 1)],
            'paginate' => $paginate,
        ];
    }

    public function saveAndFlush(ProductCategory $entity): void
    {
        $entity->setUpdatedAt(new DateTimeImmutable());

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }

    public function removeAndFlush(ProductCategory $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush($entity);
    }
}

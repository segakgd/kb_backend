<?php

namespace App\Repository\Ecommerce;

use App\Entity\Ecommerce\Product;
use App\Helper\CommonHelper;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
     * @throws \Exception
     */
    public function findProductsByCategoryName(string $categoryName, int $page = 1, int $total = 1): array
    {
        $queryBuilder = $this->createQueryBuilder('pc')
//            ->where('categoryName = :categoryName')
//            ->setParameter('categoryName', $categoryName)
            ->setFirstResult($page - 1)
            ->setMaxResults($total)
        ;

        $queryBuilder2 = $this->createQueryBuilder('pc')
//            ->where('categoryName = :categoryName')
//            ->setParameter('categoryName', $categoryName)
        ;

        $paginate = CommonHelper::buildPaginate($page, count($queryBuilder2->getQuery()->execute()));

        return [
            'items' => $queryBuilder->getQuery()->execute(),
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

//    public function getPaginatedPosts($page = 1, $postsPerPage = 9)
//    {
//        $query = $this->createQueryBuilder('a')
//            ->orderBy('a.publishedAt', 'DESC')
//            ->getQuery();
//        $paginator = new        Paginator    ($query);
//        $paginator->getQuery()
//            ->setFirstResult($postsPerPage * ($page - 1))
//            ->setMaxResults($postsPerPage);
//        return $paginator;
//    }
//
//    /**
//     * Assuming the associated Article entity has a many to one relationship to some Category entity.
//     */
//    public function getPaginatedPostsFromCategory($page = 1, $postsPerPage = 9, $category)
//    {
//        $query = $this->createQueryBuilder('a')
//            ->leftJoin('a.category', 'c')
//            ->andWhere('c.slug = :val')
//            ->setParameter('val', $category)
//            ->orderBy('a.timestamp', 'DESC')
//            ->getQuery();
//        $paginator = new        Paginator    ($query);
//        $paginator->getQuery()
//            ->setFirstResult($postsPerPage * ($page - 1))
//            ->setMaxResults($postsPerPage);
//        return $paginator;
//    }
//
//    /**
//     * Assuming the associated Article entity has a many to many relationship to some Tags entity.
//     */
//    public function getPaginatedPostsFromTag($page = 1, $postsPerPage = 9, $tag)
//    {
//        $query = $this->createQueryBuilder('a')
//            ->leftJoin('a.tags', 'c')
//            ->andWhere('c.slug = :val')
//            ->setParameter('val', $tag)
//            ->orderBy('a.publishedAt', 'DESC')
//            ->getQuery();
//        $paginator = new        Paginator    ($query);
//        $paginator->getQuery()
//            ->setFirstResult($limit * ($page - 1))
//            ->setMaxResults($limit);
//        return $paginator;
//    }
}

<?php

namespace App\Repository\User;

use App\Entity\User\Project;
use App\Entity\User\User;
use App\Repository\Dto\PaginateDto;
use App\Repository\Dto\PaginationCollection;
use App\Service\Common\Project\Dto\SearchProjectDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 *
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function search(User $user, SearchProjectDto $searchProjectDto, int $limit = 9): PaginationCollection
    {
        $page = $searchProjectDto->getPage() ?? 1;

        $userId = [$user->getId()];

        $builder = $this->createQueryBuilder('project')
            ->leftJoin('project.users', 'projectUsers')
            ->where('projectUsers.id = (:userId)')
            ->setParameter('userId', $userId)
            ->setMaxResults($limit);

        $query = $builder->getQuery();

        $items = $query->execute();

        $collection = new PaginationCollection();

        $totalItems = count($items);
        $totalPages = ceil($totalItems / $limit);
        $lastPage = $page === 1 ? null : $page - 1;
        $nextPage = $totalPages < $page + 1 ? null : $page + 1;

        $paginate = (new PaginateDto())
            ->setCurrentPage($page)
            ->setLastPage($lastPage)
            ->setNextPage($nextPage)
            ->setTotalItems($totalItems)
            ->setTotalPages($totalPages);

        $collection->setItems($items);
        $collection->setPaginate($paginate);

        return $collection;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function projectsCountByUser(User $user): int
    {
        $qb = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->leftJoin('p.users', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $user->getId());

        $query = $qb->getQuery();

        return (int)$query->getSingleScalarResult();
    }

    public function saveAndFlush(Project $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function removeAndFlush(Project $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}

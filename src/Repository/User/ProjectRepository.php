<?php

namespace App\Repository\User;

use App\Controller\Admin\Project\Request\ProjectSearchRequest;
use App\Entity\User\Project;
use App\Entity\User\User;
use App\Repository\Dto\PaginationCollection;
use App\Repository\PaginationTrait;
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
    use PaginationTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function search(User $user, ProjectSearchRequest $projectSearchRequest, int $limit = 9): PaginationCollection
    {
        $page = $projectSearchRequest->getPage() ?? 1;
        $status = $projectSearchRequest->getStatus();

        $userId = [$user->getId()];

        $builder = $this->createQueryBuilder('project')
            ->leftJoin('project.users', 'projectUsers')
            ->where('projectUsers.id = (:userId)')
            ->setParameter('userId', $userId)
            ->setMaxResults($limit);

        if (!is_null($status)) {
            $builder->andWhere('project.status = :status')
                ->setParameter('status', $status);
        }

        $builder->setMaxResults($limit);

        $items = $builder->getQuery()->execute();

        $countBuilder = $this->createQueryBuilder('project')
            ->select('COUNT(project.id)')
            ->leftJoin('project.users', 'projectUsers')
            ->where('projectUsers.id = (:userId)')
            ->setParameter('userId', $userId);

        if (!is_null($status)) {
            $countBuilder->andWhere('project.status = :status')
                ->setParameter('status', $status);
        }

        $totalItems = (int) $countBuilder->getQuery()->getSingleScalarResult();

        return static::makePaginate($items, $page, $limit, $totalItems);
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

        return (int) $query->getSingleScalarResult();
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

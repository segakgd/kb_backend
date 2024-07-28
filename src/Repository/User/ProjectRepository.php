<?php

namespace App\Repository\User;

use App\Entity\User\Project;
use App\Entity\User\User;
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

    public function findByUser(User $user): array
    {
        $userId = [$user->getId()];

        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.users', 'bc')
            ->where('bc.id IN (:userId)')->setParameter('userId', $userId);

        $query = $qb->getQuery();

        return $query->execute();
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

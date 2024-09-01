<?php

namespace App\Repository\User;

use App\Controller\Admin\Bot\Request\BotSearchRequest;
use App\Entity\User\Bot;
use App\Entity\User\Project;
use App\Repository\Dto\PaginationCollection;
use App\Repository\PaginationTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bot>
 *
 * @method Bot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bot[]    findAll()
 * @method Bot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BotRepository extends ServiceEntityRepository
{
    use PaginationTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bot::class);
    }

    public function search(Project $project, BotSearchRequest $requestDto, int $limit = 9): PaginationCollection
    {
        $page = $requestDto->getPage() ?? 1;
        $status = $requestDto->getStatus();
        $offset = ($page - 1) * $limit;

        $builder = $this->createQueryBuilder('bot')
            ->where('bot.projectId = (:projectId)')
            ->setParameter('projectId', $project->getId())
            ->setMaxResults($limit);

        if (!is_null($status)) {
            $builder
                ->andWhere('bot.status = :status')
                ->setParameter('status', $status);
        }

        $builder->setMaxResults($limit);

        $builder->orderBy('bot.id', 'DESC');
        $builder->setFirstResult($offset);

        return static::makePaginate(
            items: $builder->getQuery()->execute(),
            page: $page,
            limit: $limit,
            totalItems: $this->countByProject($project, $status),
        );
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countByProject(Project $project, ?string $status = null): int
    {
        $countBuilder = $this->createQueryBuilder('bot')
            ->select('COUNT(bot.id)')
            ->where('bot.projectId = (:projectId)')
            ->setParameter('projectId', $project->getId());

        if (!is_null($status)) {
            $countBuilder
                ->andWhere('bot.status = :status')
                ->setParameter('status', $status);
        }

        return (int) $countBuilder->getQuery()->getSingleScalarResult();
    }

    public function saveAndFlush(Bot $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function removeAndFlush(Bot $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}

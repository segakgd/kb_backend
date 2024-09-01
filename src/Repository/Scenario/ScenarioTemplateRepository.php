<?php

namespace App\Repository\Scenario;

use App\Controller\Admin\Project\Request\ProjectSearchRequest;
use App\Controller\Admin\ScenarioTemplate\Request\ScenarioTemplateSearchRequest;
use App\Entity\Scenario\ScenarioTemplate;
use App\Entity\User\Project;
use App\Entity\User\User;
use App\Repository\Dto\PaginationCollection;
use App\Repository\PaginationTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ScenarioTemplate>
 *
 * @method ScenarioTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScenarioTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScenarioTemplate[]    findAll()
 * @method ScenarioTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScenarioTemplateRepository extends ServiceEntityRepository
{
    use PaginationTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScenarioTemplate::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function search(Project $project, ScenarioTemplateSearchRequest $requestDto, int $limit = 9): PaginationCollection
    {
        $page = $requestDto->getPage() ?? 1;
        $status = $requestDto->getStatus();
        $offset = ($page - 1) * $limit;

        $builder = $this->createQueryBuilder('scenarioTemplate')
            ->where('scenarioTemplate.projectId = (:projectId)')
            ->setParameter('projectId', $project->getId())
            ->setMaxResults($limit);

        if (!is_null($status)) {
            $builder
                ->andWhere('scenarioTemplate.status = :status')
                ->setParameter('status', $status);
        }

        $builder->setMaxResults($limit);

        $builder->orderBy('scenarioTemplate.id', 'DESC');
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
        $countBuilder = $this->createQueryBuilder('scenarioTemplate')
            ->select('COUNT(scenarioTemplate.id)')
            ->where('scenarioTemplate.projectId = (:projectId)')
            ->setParameter('projectId', $project->getId());

        if (!is_null($status)) {
            $countBuilder
                ->andWhere('scenarioTemplate.status = :status')
                ->setParameter('status', $status);
        }

        return (int) $countBuilder->getQuery()->getSingleScalarResult();
    }

    public function saveAndFlush(ScenarioTemplate $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(ScenarioTemplate $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}

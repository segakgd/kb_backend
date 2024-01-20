<?php

namespace App\Repository\Scenario;

use App\Entity\Scenario\ScenarioTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScenarioTemplate::class);
    }
}

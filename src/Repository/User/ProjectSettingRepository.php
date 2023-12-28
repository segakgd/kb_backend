<?php

namespace App\Repository\User;

use App\Entity\User\ProjectSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectSetting>
 *
 * @method ProjectSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectSetting[]    findAll()
 * @method ProjectSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectSettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectSetting::class);
    }

    public function saveAndFlush(ProjectSetting $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }
}

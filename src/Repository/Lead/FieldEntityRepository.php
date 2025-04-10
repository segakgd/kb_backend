<?php

declare(strict_types=1);

namespace App\Repository\Lead;

use App\Entity\Lead\DealField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DealField>
 *
 * @method DealField|null find($id, $lockMode = null, $lockVersion = null)
 * @method DealField|null findOneBy(array $criteria, array $orderBy = null)
 * @method DealField[]    findAll()
 * @method DealField[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FieldEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DealField::class);
    }

    public function removeFieldsByIds(array $ids): void
    {
        if (empty($ids)) {
            return;
        }

        $queryBuilder = $this->createQueryBuilder('field');

        $queryBuilder
            ->delete()
            ->andWhere($queryBuilder->expr()->in('field.id', $ids));

        $queryBuilder->getQuery()->getResult();
    }

    public function saveAndFlush(DealField $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function removeAndFlush(DealField $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}

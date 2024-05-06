<?php

namespace App\Repository\User;

use App\Entity\User\Tariff;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tariff>
 *
 * @method Tariff|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tariff|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tariff[]    findAll()
 * @method Tariff[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TariffRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tariff::class);
    }

    public function saveAndFlush(Tariff $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }
}

<?php

namespace App\Repository\User;

use App\Entity\User\Bot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bot::class);
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

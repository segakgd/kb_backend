<?php

namespace App\Repository\Lead;

use App\Entity\Lead\DealContacts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DealContacts>
 *
 * @method DealContacts|null find($id, $lockMode = null, $lockVersion = null)
 * @method DealContacts|null findOneBy(array $criteria, array $orderBy = null)
 * @method DealContacts[]    findAll()
 * @method DealContacts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactsEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DealContacts::class);
    }

    public function saveAndFlush(DealContacts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function removeAndFlush(DealContacts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}

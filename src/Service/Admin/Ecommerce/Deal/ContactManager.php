<?php

namespace App\Service\Admin\Ecommerce\Deal;

use App\Dto\deprecated\Ecommerce\ContactsDto;
use App\Entity\Lead\DealContacts;
use App\Repository\Lead\ContactsEntityRepository;
use DateTimeImmutable;

class ContactManager implements ContactManagerInterface
{
    public function __construct(
        private readonly ContactsEntityRepository $contactsEntityRepository,
    ) {
    }

    public function add(ContactsDto $dto): DealContacts
    {
        $entity = (new DealContacts());

        $entity
            ->setFirstName($dto->getFirstName())
            ->setLastName($dto->getLastName())
            ->setPhone($dto->getPhone())
            ->setEmail($dto->getEmail())
            ->setCreatedAt(new DateTimeImmutable())
        ;

        $this->contactsEntityRepository->saveAndFlush($entity);

        return $entity;
    }

    public function update(ContactsDto $dto): ?DealContacts
    {
        $entity = null;

        if ($dto->getId()){
            $entity = $this->contactsEntityRepository->find($dto->getId());
        }

        if (!$entity){
            $entity = (new DealContacts());
        }

        $entity
            ->setFirstName($dto->getFirstName())
            ->setLastName($dto->getLastName())
            ->setPhone($dto->getPhone())
            ->setEmail($dto->getEmail())
        ;

        $this->contactsEntityRepository->saveAndFlush($entity);

        return $entity;
    }
}

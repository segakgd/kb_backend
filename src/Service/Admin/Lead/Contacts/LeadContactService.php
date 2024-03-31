<?php

declare(strict_types=1);

namespace App\Service\Admin\Lead\Contacts;

use App\Controller\Admin\Lead\DTO\Request\Field\LeadContactsReqDto;
use App\Entity\Lead\DealContacts;
use App\Repository\Lead\ContactsEntityRepository;

class LeadContactService
{
    public function __construct(private readonly ContactsEntityRepository $contactsEntityRepository)
    {
    }

    public function add(LeadContactsReqDto $contactsDto): DealContacts
    {
        $email = $contactsDto->getEmail()?->getValue();
        $phone = $contactsDto->getPhone()?->getValue();
        $firstName = $contactsDto->getFirstName()?->getValue();
        $lastName = $contactsDto->getLastName()?->getValue();


        $contactsEntity = (new DealContacts())
            ->setEmail($email)
            ->setPhone($phone)
            ->setFirstName($firstName)
            ->setLastName($lastName);

        return $this->saveToDb($contactsEntity);
    }

    private function saveToDb(DealContacts $contacts): DealContacts
    {
        $this->contactsEntityRepository->saveAndFlush($contacts);

        return $contacts;
    }
}

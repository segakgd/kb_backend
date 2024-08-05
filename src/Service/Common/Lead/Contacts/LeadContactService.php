<?php

declare(strict_types=1);

namespace App\Service\Common\Lead\Contacts;

use App\Controller\Admin\Lead\DTO\Request\Field\LeadContactsReqDto;
use App\Entity\Lead\DealContacts;
use App\Repository\Lead\ContactsEntityRepository;

readonly class LeadContactService
{
    public function __construct(private ContactsEntityRepository $contactsEntityRepository) {}

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

        return $this->save($contactsEntity);
    }

    public function updateOrCreate(LeadContactsReqDto $contactsReqDto, ?DealContacts $contacts): DealContacts
    {
        if (is_null($contacts)) {
            $contacts = new DealContacts();
        }

        $contacts
            ->setPhone($contactsReqDto->getPhone()->getValue())
            ->setEmail($contactsReqDto->getEmail()->getValue())
            ->setLastName($contactsReqDto->getLastName()->getValue())
            ->setFirstName($contactsReqDto->getFirstName()->getValue());

        return $this->save($contacts);
    }

    private function save(DealContacts $contacts): DealContacts
    {
        $this->contactsEntityRepository->saveAndFlush($contacts);

        return $contacts;
    }
}

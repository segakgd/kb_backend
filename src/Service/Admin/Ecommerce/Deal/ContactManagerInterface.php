<?php

namespace App\Service\Admin\Ecommerce\Deal;

use App\Dto\Ecommerce\ContactsDto;
use App\Entity\Lead\DealContacts;

interface ContactManagerInterface
{
    public function add(ContactsDto $dto): DealContacts;

    public function update(ContactsDto $dto): ?DealContacts;
}
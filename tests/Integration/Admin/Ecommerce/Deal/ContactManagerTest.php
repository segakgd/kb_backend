<?php

namespace App\Tests\Integration\Admin\Ecommerce\Deal;

use App\Dto\deprecated\Ecommerce\ContactsDto;
use App\Service\Admin\Ecommerce\Deal\ContactManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactManagerTest extends KernelTestCase
{
    /**
     * @throws Exception
     */
    public function testSomething(): void
    {
        $container = static::getContainer();

        /** @var ContactManager $contactManager */
        $contactManager = $container->get(ContactManager::class);

        $contactsDto = (new ContactsDto)
            ->setFirstName('asdasd')
            ->setPhone('asdasdasd')
            ->setEmail('asdasd')
            ->setLastName('asdasd')
        ;

        dd($contactManager->add($contactsDto));
    }
}

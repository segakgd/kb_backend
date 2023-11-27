<?php

namespace App\Tests\Functional\Trait\User;

use App\Entity\User\User;
use Doctrine\Persistence\ObjectManager;

trait UserTrait
{
    public function createUser(ObjectManager $manager): User
    {
        $user = (new User())
            ->setEmail('test' . uniqid() . '@test.com')
            ->setPassword('123456')
        ;

        $manager->persist($user);
        $manager->flush($user);

        return $user;
    }
}
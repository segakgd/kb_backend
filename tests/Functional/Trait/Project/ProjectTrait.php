<?php

namespace App\Tests\Functional\Trait\Project;

use App\Entity\User\Project;
use App\Entity\User\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;

trait ProjectTrait
{
    public function createProject(ObjectManager $manager, User $user): Project
    {
        $project = (new Project())
            ->setName('Проект тестовый')
            ->setActiveFrom(new DateTimeImmutable())
            ->setActiveTo(new DateTimeImmutable())
            ->addUser($user)
        ;

        $manager->persist($project);

        return $project;
    }
}

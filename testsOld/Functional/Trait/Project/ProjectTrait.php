<?php

namespace App\Tests\Functional\Trait\Project;

use App\Entity\User\Project;
use App\Entity\User\User;
use Doctrine\Persistence\ObjectManager;

trait ProjectTrait
{
    public function createProject(ObjectManager $manager, User $user): Project
    {
        $project = (new Project())
            ->setName('TestName')
            ->addUser($user)
        ;

        $manager->persist($project);
        $manager->flush($project);

        return $project;
    }
}
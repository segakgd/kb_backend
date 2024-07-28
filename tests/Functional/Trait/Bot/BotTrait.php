<?php

namespace App\Tests\Functional\Trait\Bot;

use App\Entity\User\Bot;
use App\Entity\User\Project;
use Doctrine\Persistence\ObjectManager;

trait BotTrait
{
    public function createBot(ObjectManager $manager, Project $project): Bot
    {
        $project = (new Bot())
            ->setName('Мой новый бот')
            ->setType('telegram')
            ->setProjectId($project->getId())
            ->setToken('token_token_token_token_token_token_token_token');

        $manager->persist($project);

        return $project;
    }
}

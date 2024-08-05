<?php

namespace App\DataFixtures;

use App\Entity\User\Enum\ProjectStatusEnum;
use App\Entity\User\Project;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

class ProjectFixtures extends Fixture
{
    private const ADMIN_EMAIL = 'admin@test.email';

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $projectRepository = $manager->getRepository(Project::class);

        $user = $projectRepository->findOneBy(
            [
                'email' => static::ADMIN_EMAIL,
            ]
        );

        if ($user) {
            throw new Exception('Не нашёл админа');
        }

        $project = (new Project())
            ->setName('Сгенерированный проект')
            ->setStatus(ProjectStatusEnum::Active)
            ->addUser($user)
            ->setActiveFrom(new DateTimeImmutable())
            ->setActiveTo(new DateTimeImmutable('12.12.2025'));

        $manager->persist($project);
        $manager->flush();
    }
}

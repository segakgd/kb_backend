<?php

namespace App\Tests\Functional\Trait\Project;

use App\Entity\User\Project;
use App\Entity\User\ProjectSetting;
use App\Entity\User\Tariff;
use App\Entity\User\User;
use App\Service\Common\Project\TariffService;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;

trait ProjectTrait
{
    private ?Project $project;

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function initProject(ObjectManager $manager, User $user): Project
    {
        $project = $this->createProject($manager, $user);

        $manager->flush($project);

        $tariff = $this->getDefaultTariff($manager);
        $this->createProjectSetting($manager, $project, $tariff);

        return $project;
    }

    public function createProject(ObjectManager $manager, User $user): Project
    {
        $project = (new Project())
            ->setName('Проект тестовый')
            ->setActiveFrom(new DateTimeImmutable())
            ->setActiveTo(new DateTimeImmutable())
            ->addUser($user)
        ;

        $manager->persist($project);

        $this->project = $project;

        return $project;
    }

    public function getDefaultTariff(ObjectManager $manager): Tariff
    {
        $tariffRepository = $manager->getRepository(Tariff::class);

        $tariff = $tariffRepository->findOneBy(
            [
                'code' => TariffService::DEFAULT_TARIFF_CODE
            ]
        );

        if (!$tariff){
            $tariff = $tariff->createTestTariff($manager);
        }

        return $tariff;
    }

    public function createTestTariff(ObjectManager $manager): Tariff
    {
        $tariff = (new Tariff())
            ->setName('Тестовый тариф')
            ->setPrice(10000)
            ->setPriceWF('100,00')
            ->setCode('TEST' . uniqid())
            ->setDescription('For test')
            ->setActive(true)
        ;

        $manager->persist($tariff);

        return $tariff;
    }

    public function createProjectSetting(ObjectManager $manager, Project $project, Tariff $tariff): ProjectSetting
    {
        $projectSetting = (new ProjectSetting())
            ->setProjectId($project->getId())
            ->setBasic(
                [
                    'country' => 'russia',
                    'language' => 'ru',
                    'timeZone' => 'Europe/Moscow',
                    'currency' => 'RUB',
                ]
            )
            ->setNotification(
                [
                    'aboutNewLead' => [
                        'system' => true,
                        'mail' => false,
                        'sms' => false,
                        'telegram' => false,
                    ],
                    'aboutChangesStatusLead' => [
                        'system' => true,
                        'mail' => false,
                        'sms' => false,
                        'telegram' => false,
                    ]
                ]
            )
            ->setTariffId($tariff->getId())
        ;

        $manager->persist($projectSetting);

        return $projectSetting;
    }
}

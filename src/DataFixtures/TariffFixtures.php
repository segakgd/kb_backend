<?php

namespace App\DataFixtures;

use App\Entity\User\Tariff;
use App\Service\Common\Project\TariffService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TariffFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $defaultTariff = new Tariff();
        $defaultTariff
            ->setCode(TariffService::DEFAULT_TARIFF_CODE)
            ->setName('Default Tariff')
            ->setPrice(0)
            ->setPriceWF('0.00')
            ->setDescription('System tariff')
            ->setActive(true);

        $manager->persist($defaultTariff);
        $manager->flush();
    }
}

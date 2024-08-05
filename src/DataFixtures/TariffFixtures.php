<?php

namespace App\DataFixtures;

use App\Entity\User\Tariff;
use App\Service\Common\Project\Enum\TariffCodeEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TariffFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder(): int
    {
        return 1;
    }

    public function load(ObjectManager $manager): void
    {
        $tariffRepository = $manager->getRepository(Tariff::class);

        $defaultTariff = $tariffRepository->findOneBy(
            [
                'code' => TariffCodeEnum::Trial->value,
            ]
        );

        if (!is_null($defaultTariff)) {
            return;
        }

        $defaultTariff = (new Tariff())
            ->setName('Триал')
            ->setActive(true)
            ->setCode(TariffCodeEnum::Trial)
            ->setPrice(0)
            ->setDescription('Триал период')
            ->setPriceWF(0.0);

        $manager->persist($defaultTariff);
        $manager->flush();
    }
}

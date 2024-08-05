<?php

namespace App\DataFixtures;

use App\Entity\User\User;
use App\Service\Common\Security\SecurityService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    private const ADMIN_EMAIL = 'admin@test.email';
    private const PASSWORD = '12345678';

    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly SecurityService $securityService,
    ) {}

    public function getOrder(): int
    {
        return 2;
    }

    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $userRepository = $manager->getRepository(User::class);

        $user = $userRepository->findOneBy(
            [
                'email' => static::ADMIN_EMAIL,
            ]
        );

        if (!is_null($user)) {
            return;
        }

        $user = (new User())
            ->setEmail(static::ADMIN_EMAIL)
            ->setRoles(['ROLE_ADMIN']);

        $password = $this->userPasswordHasher->hashPassword($user, static::PASSWORD);

        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();

        $this->securityService->refreshAccessToken($user);
        $this->securityService->resetRefreshToken($user);
    }
}

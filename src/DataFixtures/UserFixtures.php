<?php

namespace App\DataFixtures;

use App\Entity\User\User;
use App\Service\Common\Security\SecurityService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private const EMAIL = 'admin@test.email';
    private const PASSWORD = '12345678';

    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly SecurityService $securityService,
    ) {}

    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $userRepository = $manager->getRepository(User::class);

        $user = $userRepository->findOneBy(
            [
                'email' => static::EMAIL,
            ]
        );

        if ($user) {
            return;
        }

        $password = $this->userPasswordHasher->hashPassword($user, static::PASSWORD);

        $user = (new User())
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($password);

        $this->securityService->refreshAccessToken($user);
        $this->securityService->resetRefreshToken($user);
    }
}

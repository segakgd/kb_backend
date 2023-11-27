<?php

namespace App\Service\Common\Security;

use App\Dto\Security\UserDto;
use App\Entity\User\User;
use App\Exception\Security\UserExistException;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityService
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function createUser(UserDto $userDto): User
    {
        if ($this->userRepository->isUserExists($userDto->getEmail())) {
            throw new UserExistException('User exists with email: ' . $userDto->getEmail());
        }

        $user = new User();
        $password = $this->userPasswordHasher->hashPassword($user, $userDto->getPassword());

        $user->setEmail($userDto->getEmail());
        $user->setPassword($password);

        $this->entityManager->persist($user);
        $this->entityManager->flush($user);

        return $user;
    }
}
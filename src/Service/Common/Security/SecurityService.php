<?php

namespace App\Service\Common\Security;

use App\Controller\Security\DTO\AuthDto;
use App\Dto\Security\UserDto;
use App\Entity\User\User;
use App\Exception\Security\UserExistException;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Random\RandomException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class SecurityService
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

    /**
     * @throws Exception
     */
    public function identifyUser(AuthDto $authDto): User
    {
        $userName = $authDto->getUsername();

        $user = $this->userRepository->findOneBy(['email' => $userName]);
        $password = $this->userPasswordHasher->hashPassword($user, $authDto->getPassword());

        if ($user->getPassword() !== $password) {
            throw new Exception('Wrong password for user: ' . $user->getEmail());
        }

        return $user;
    }

    /**
     * @throws RandomException
     */
    public function refresh(User $user): string
    {
        $user->setAccessToken($this->generateAccessToken($user->getId()));

        $this->entityManager->persist($user);
        $this->entityManager->flush($user);

        return $user->getAccessToken();
    }

    /**
     * @throws RandomException
     */
    function generateAccessToken($userId, $expiryTime = 3600): string
    {
        $issuedAt = time();

        $expirationTime = $issuedAt + $expiryTime;

        $data = [
            "userId" => $userId . bin2hex(random_bytes(32)),
            "iat" => $issuedAt,
            "exp" => $expirationTime,
        ];

        return base64_encode(json_encode($data));
    }
}

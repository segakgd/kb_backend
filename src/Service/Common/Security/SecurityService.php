<?php

namespace App\Service\Common\Security;

use App\Controller\Security\DTO\AuthDto;
use App\Controller\Security\DTO\ReloadAccessDto;
use App\Dto\Security\UserDto;
use App\Entity\User\User;
use App\Exception\Security\UserExistException;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Random\RandomException;
use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

readonly class SecurityService
{
    private const ACCESS_EXPIRATION_TIME_SECONDS = 3600;

    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
    ) {}

    /**
     * @throws RandomException
     */
    public function reloadAccess(ReloadAccessDto $reloadAccessDto): string
    {
        $user = $this->userRepository->getByRefreshToken($reloadAccessDto->getRefreshToken());

        if (is_null($user)) {
            throw new AccessDeniedException('Refresh token not found for this user');
        }

        $accessToken = $this->refreshAccessToken($user);

        $this->entityManager->refresh($user);

        $user->setAccessToken($accessToken);

        $this->entityManager->persist($user);
        $this->entityManager->flush($user);

        return $accessToken;
    }

    /**
     * @throws Exception
     */
    public function createUser(UserDto $userDto, ?string $userRole): User
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

        $user->setAccessToken($this->generateAccessToken($user->getId()));
        $user->setRefreshTokens($this->generateRefreshToken());

        if (!is_null($userRole)) {
            $user->setRoles([$userRole]);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush($user);

        return $user;
    }

    /**
     * @throws RandomException
     */
    public function resetRefreshToken(User $user): void
    {
        $user->setRefreshTokens($this->generateRefreshToken());

        $this->entityManager->persist($user);
        $this->entityManager->flush($user);
    }

    /**
     * @throws UserNotFoundException
     * @throws InvalidPasswordException
     */
    public function identifyUser(AuthDto $authDto): User
    {
        $userName = $authDto->getUsername();

        $user = $this->userRepository->findOneBy(['email' => $userName]);

        if (null === $user) {
            throw new UserNotFoundException();
        }

        if (!$this->userPasswordHasher->isPasswordValid($user, $authDto->getPassword())) {
            throw new InvalidPasswordException('Wrong password for user: ' . $user->getEmail());
        }

        return $user;
    }

    /**
     * @throws RandomException
     */
    public function refreshAccessToken(User $user): string
    {
        $token = $this->generateAccessToken($user->getId());

        $user->setAccessToken($token);

        $this->entityManager->persist($user);
        $this->entityManager->flush($user);

        return $user->getAccessToken();
    }

    /**
     * @throws RandomException
     */
    private function generateAccessToken(int $userId): string
    {
        $issuedAt = time();

        $expirationTime = $issuedAt + static::ACCESS_EXPIRATION_TIME_SECONDS;

        $data = [
            'userId' => $userId . bin2hex(random_bytes(32)),
            'iat'    => $issuedAt,
            'exp'    => $expirationTime,
        ];

        return base64_encode(json_encode($data));
    }

    /**
     * @throws RandomException
     */
    private function generateRefreshToken(): string
    {
        return bin2hex(random_bytes(64));
    }
}

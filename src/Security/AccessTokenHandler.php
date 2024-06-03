<?php

namespace App\Security;

use App\Repository\User\UserRepository;
use Exception;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

readonly class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $user = $this->userRepository->findOneBy(
            [
                'accessToken' => $accessToken,
            ]
        );

        if (null === $user) {
            throw new Exception('Invalid credentials.');
        }

        return new UserBadge($user->getEmail());
    }
}
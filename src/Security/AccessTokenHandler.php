<?php

namespace App\Security;

//use App\Repository\AccessTokenRepository;
use App\Repository\User\UserRepository;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        //private AccessTokenRepository $repository

        private readonly UserRepository $userRepository,
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $this->userRepository->find(1);

//        dd($accessToken);

        // e.g. query the "access token" database to search for this token
        //$accessToken = $this->repository->findOneByValue($accessToken);

//        if (null === $accessToken || !$accessToken->isValid()) {
//            throw new \Exception('Invalid credentials.');
//        }

        // and return a UserBadge object containing the user identifier from the found token
        // (this is the same identifier used in Security configuration; it can be an email,
        // a UUUID, a username, a database ID, etc.)
        return new UserBadge('hi@mail.ru');
    }
}
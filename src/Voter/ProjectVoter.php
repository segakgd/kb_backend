<?php

namespace App\Voter;

use App\Entity\User\Project;
use App\Entity\User\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProjectVoter extends Voter
{
    public const EXIST_USER_PERMISSION = 'existUser';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if ($attribute != self::EXIST_USER_PERMISSION) {
            return false;
        }

        if (!$subject instanceof Project) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Project $project */
        $project = $subject;

        return match ($attribute) {
            self::EXIST_USER_PERMISSION => $this->isUserExistInProject($project, $user),
            default                     => throw new LogicException('Access error')
        };
    }

    /**
     * У пользователя есть права к проекту
     */
    private function isUserExistInProject(Project $project, User $user): bool
    {
        return $project->getUsers()->exists(
            function ($key, $projectUser) use ($user) {
                return $projectUser->getId() === $user->getId();
            }
        );
    }
}

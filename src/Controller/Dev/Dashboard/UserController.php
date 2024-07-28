<?php

namespace App\Controller\Dev\Dashboard;

use App\Repository\User\ProjectRepository;
use App\Repository\User\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
    ) {}

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/admin/users/', name: 'admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        $usersResponse = [];

        foreach ($users as $user) {
            $role = in_array('ROLE_ADMIN', $user->getRoles()) ? 'admin' : 'user';

            $usersResponse[] = [
                'id'             => $user->getId(),
                'email'          => $user->getEmail(),
                'role'           => $role,
                'created_at'     => $user->getCreatedAt(),
                'projects_count' => $this->projectRepository->projectsCountByUser($user),
            ];
        }

        return $this->render(
            'admin/user/users.html.twig',
            [
                'users' => $usersResponse,
            ]
        );
    }
}

<?php

namespace App\Controller\Dev;

use App\Entity\User\Bot;
use App\Entity\User\Project;
use App\Entity\User\User;
use App\Entity\Visitor\VisitorSession;
use App\Repository\User\ProjectRepository;
use App\Repository\User\UserRepository;
use App\Service\Admin\DashboardService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    public function __construct(
        private readonly DashboardService $dashboardService,
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

    #[Route('/admin/user/{user}/projects/', name: 'admin_projects')]
    public function projects(User $user, ProjectRepository $projectRepository): Response
    {
        $projectRepository->findAll();

        return $this->render(
            'admin/user/projects.html.twig',
            [
                'projects' => $projectRepository->findAll(),
            ]
        );
    }

    #[Route('/admin/projects/{project}/dashboard/', name: 'admin_project_dashboard')]
    public function project(Project $project): Response
    {
        return $this->render(
            'admin/user/bots.html.twig',
            [
                'bots' => $this->dashboardService->prepareBots($project),
            ]
        );
    }

    #[Route('/admin/projects/{project}/bot/{bot}/', name: 'admin_project_bot_dashboard')]
    public function bot(Project $project, Bot $bot): Response
    {
        return $this->render(
            'admin/user/bot.html.twig',
            [
                'sessions'  => $this->dashboardService->getSessions($bot),
                'bot'       => $this->dashboardService->prepareBot($bot, $project),
                'scenarios' => $this->dashboardService->prepareScenario($project),
                'projectId' => $project->getId(),
            ]
        );
    }

    #[Route('/admin/projects/{project}/sessions/{visitorSession}/', name: 'admin_project_sessions')]
    public function session(Project $project, VisitorSession $visitorSession): Response
    {
        return $this->render(
            'admin/session.html.twig',
            [
                'projectId' => $project->getId(),
                'botId'     => $visitorSession->getBotId(),
                'messages'  => $this->dashboardService->getMessageHistory(),
                'events'    => $this->dashboardService->prepareEvents($visitorSession),
            ]
        );
    }
}

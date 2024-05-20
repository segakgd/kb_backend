<?php

namespace App\Controller\Dev;

use App\Entity\User\Bot;
use App\Entity\User\Project;
use App\Entity\Visitor\VisitorSession;
use App\Repository\User\ProjectRepository;
use App\Repository\User\UserRepository;
use App\Service\Admin\DashboardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {
    }

    #[Route('/admin/users/', name: 'admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render(
            'admin/user/users.html.twig',
            [
                'users' => $users,
            ]
        );
    }

    #[Route('/admin/projects/', name: 'admin_projects')]
    public function projects(ProjectRepository $projectRepository): Response
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
    public function projectDashboard(Project $project): Response
    {
        return $this->render(
            'admin/user/project-dashboard.html.twig',
            [
                'sessions' => $this->dashboardService->getSessions($project->getId()),
                'bots' => $this->dashboardService->prepareBots($project),
                'scenarios' => $this->dashboardService->prepareScenario($project->getId()),
            ]
        );
    }

    #[Route('/admin/projects/{project}/bot/{bot}/', name: 'admin_project_bot_dashboard')]
    public function botDashboard(Project $project, Bot $bot): Response
    {
        return $this->render(
            'admin/user/bot.html.twig',
            [
                'sessions' => $this->dashboardService->getSessions($project->getId()),
                'bot' => $this->dashboardService->prepareBot($bot, $project),
                'scenarios' => $this->dashboardService->prepareScenario($project->getId()),
            ]
        );
    }

    #[Route('/admin/projects/{project}/sessions/', name: 'admin_project_sessions')]
    public function session(VisitorSession $visitorSession): Response
    {
        // Сообщения
        // События

        return $this->render(
            'admin/user/project-dashboard.html.twig',
            [
                'messages' => $this->dashboardService->getMessageHistory(),
                'events' => $this->dashboardService->prepareEvents($visitorSession),
            ]
        );
    }
}

<?php

namespace App\Controller\Dev\Dashboard;

use App\Entity\User\Project;
use App\Entity\User\User;
use App\Service\Common\Dashboard\DashboardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {}

    #[Route('/admin/user/{user}/projects/', name: 'admin_projects')]
    public function projects(User $user): Response
    {
        return $this->render(
            'admin/user/projects.html.twig',
            [
                'projects' => $user->getProjects(),
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
}

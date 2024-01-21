<?php

namespace App\Controller\Dev\Admin;

use App\Entity\Scenario\ScenarioTemplate;
use App\Entity\User\Bot;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Service\Admin\DashboardService;
use App\Service\Common\History\HistoryErrorService;
use App\Service\Common\Project\ProjectServiceInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly ProjectServiceInterface $projectService,
        private readonly DashboardService $dashboardService,
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $project = $this->projectService->findOneById(4842);

        $dashboardData = $this->dashboardService->getDashboardForProject($project);

        return $this->render('admin/index.html.twig',
            $dashboardData
        );
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Kraiber Backend');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Боты', 'fa fa-file-text', Bot::class);
        yield MenuItem::linkToCrud('Сценарии', 'fa fa-file-text', ScenarioTemplate::class);
        yield MenuItem::linkToCrud('События', 'fa fa-file-text', VisitorEvent::class);
        yield MenuItem::linkToCrud('Сессии', 'fa fa-file-text', VisitorSession::class);
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addCssFile('/assets/style.css');
    }
}

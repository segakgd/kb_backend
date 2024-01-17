<?php

namespace App\Controller\Dev\Admin;

use App\Entity\History\History;
use App\Entity\User\Bot;
use App\Entity\User\Project;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Service\Admin\Bot\BotServiceInterface;
use App\Service\Admin\History\HistoryService;
use App\Service\Admin\History\HistoryServiceInterface;
use App\Service\Common\Project\ProjectServiceInterface;
use App\Service\Visitor\Event\VisitorEventService;
use App\Service\Visitor\Session\VisitorSessionServiceInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly HistoryServiceInterface $historyService,
        private readonly BotServiceInterface $botService,
        private readonly ProjectServiceInterface $projectService,
        private readonly VisitorSessionServiceInterface $visitorSessionService,
        private readonly VisitorEventService $visitorEventService,
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $project = $this->projectService->findOneById(4842);
        $projectId = $project->getId();

        $histories = $this->historyService->findAll($projectId);
        $bots = $this->botService->findAll($projectId);
        $sessions = $this->visitorSessionService->findAll($projectId);
        $events = $this->visitorEventService->findAllByProjectId($projectId);

        return $this->render('admin/index.html.twig',
            [
                'projectId' => $projectId,
                'histories' => $this->prepareHistory($histories),
                'bots' => $this->prepareBots($bots, $project),
                'scenario' => [],
                'commands' => $this->getCommands(),
                'sessions' => $this->prepareSessions($sessions),
                'events' => $this->prepareEvents($events),
            ]
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
//        yield MenuItem::linkToUrl('Дашборд', 'fa fa-file-text', 'asdasd');
        yield MenuItem::linkToCrud('Боты', 'fa fa-file-text', Bot::class);
//        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addCssFile('/assets/style.css');
    }


    private function prepareEvents(array $events): array
    {
        $prepareEvents = [];

        /** @var VisitorEvent $event */
        foreach ($events as $event){
            $prepareEvent = [
                'id' => $event->getId(),
                'type' => $event->getType(),
                'status' => $event->getStatus(),
                'createdAt' => $event->getCreatedAt(),
            ];

            $prepareEvents[] = $prepareEvent;
        }

        return $prepareEvents;
    }

    private function prepareSessions(array $sessions): array
    {
        $prepareSessions = [];

        /** @var VisitorSession $session */
        foreach ($sessions as $session){
            $visitorEvent = null;

            if ($session->getVisitorEvent()){
                $visitorEvent = $this->visitorEventService->findOneById($session->getVisitorEvent());
            }

            $prepareSession = [
                'sessionName' => $session->getName(),
                'sessionChannel' => $session->getChannel(),
            ];

            if ($visitorEvent){
                $prepareSession['sessionVisitorEvent'] = [
                    'type' => $visitorEvent->getType(),
                    'status' => $visitorEvent->getStatus(),
                ];
            }

            $prepareSessions[] = $prepareSession;
        }

        return $prepareSessions;
    }

    private function getCommands(): array
    {
        return [
            [
                'commandName' => 'Обработать события',
                'commandCode' => 'kb:tg:handler_events',
                'commandDescription' => 'Обрабатывает события находящиеся в очереди со статусом new',
            ]
        ];
    }

    private function prepareBots(array $bots, Project $project): array
    {
        $prepareBots = [];

        $projectName = $project->getName();

        /** @var Bot $bot */
        foreach ($bots as $bot){
            $prepareBot = [
                'projectName' => $projectName,
                'botId' => $bot->getId(),
                'botName' => $bot->getName(),
                'botType' => $bot->getType(),
                'botToken' => $bot->getToken(),
                'botActive' => $bot->isActive(),
                'webhookUri' => $bot->getWebhookUri() ?? '',
            ];

            $prepareBots[] = $prepareBot;
        }

        return $prepareBots;
    }

    private function prepareHistory(array $histories): array
    {
        $prepareHistories = [];

        /** @var History $history */
        foreach ($histories as $history){
            $prepareHistory = [
                'createdAt' => $history->getCreatedAt(),
                'status' => $history->getStatus(),
                'type' => $this->getNormalizedType($history->getType()),
                'sender' => $history->getSender(),
                'recipient' => $history->getRecipient(),
            ];

            if ($history->getStatus() === HistoryService::HISTORY_STATUS_ERROR){
                $prepareHistory['errorMessage'] = $this->getNormalizedErrorMessage();
            }

            $prepareHistories[] = $prepareHistory;
        }

        return $prepareHistories;
    }

    private function getNormalizedType(string $type): string
    {
        return match ($type){
            HistoryService::HISTORY_TYPE_NEW_LEAD => 'новая заявка',
            HistoryService::HISTORY_TYPE_SEND_MESSAGE_TO_CHANNEL => 'отправка данных в сторонний сервис (интеграции)',
            HistoryService::HISTORY_TYPE_SEND_MESSAGE_TO_TELEGRAM_CHANNEL => 'отправка уведомлений в telegram',
            HistoryService::HISTORY_TYPE_LOGIN => 'вход в систему',
        };
    }

    private function getNormalizedErrorMessage(): string
    {
        return 'Пока что для примера просто оставлю это сообщение.';
    }
}

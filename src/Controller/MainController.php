<?php

namespace App\Controller;

use App\Entity\History\History;
use App\Entity\User\Bot;
use App\Entity\User\Project;
use App\Service\Admin\Bot\BotServiceInterface;
use App\Service\Admin\History\HistoryService;
use App\Service\Admin\History\HistoryServiceInterface;
use App\Service\Common\Project\ProjectServiceInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    public function __construct(
        private readonly HistoryServiceInterface $historyService,
        private readonly BotServiceInterface $botService,
        private readonly ProjectServiceInterface $projectService,
    ) {
    }

    /**
     * @throws Exception
     */
    #[Route('/', name: 'app_main', methods: ['GET'])]
    public function main(): Response
    {
        $project = $this->projectService->findOneById(4842);

        $histories = $this->historyService->findAll(4842);
        $bots = $this->botService->findAll(4842);

        return $this->render(
            'main/index.html.twig',
            [
                'histories' => $this->prepareHistory($histories),
                'bots' => $this->prepareBots($bots, $project),
                'scenario' => [],
                'commands' => $this->getCommands(),
                'sessions' => [],
                'events' => [],
            ]
        );
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
                'botName' => $bot->getName(),
                'botType' => $bot->getType(),
                'botToken' => $bot->getToken(),
                'botActive' => $bot->isActive(),
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

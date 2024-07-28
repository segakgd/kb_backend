<?php

namespace App\Controller\Dev\Events;

use App\Converter\ScenarioConverter;
use App\Dto\Scenario\ScenarioCollection;
use App\Entity\User\Bot;
use App\Entity\User\Project;
use App\Event\InitWebhookBotEvent;
use App\Repository\Scenario\ScenarioTemplateRepository;
use App\Service\Admin\Bot\BotServiceInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class BotEventsController extends AbstractController
{
    public function __construct(
        private readonly BotServiceInterface $botService,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly ScenarioConverter $settingConverter,
        private readonly SerializerInterface $serializer,
        private readonly ScenarioTemplateRepository $scenarioTemplateRepository,
    ) {}

    #[Route('/dev/project/{project}/bot/{botId}/activate/', name: 'dev_bot_activate', methods: ['GET'])]
    public function botActivate(Project $project, int $botId): RedirectResponse
    {
        $this->botService->updateStatus($botId, $project->getId(), true);

        return new RedirectResponse("/admin/projects/{$project->getId()}/dashboard/");
    }

    #[Route('/dev/project/{project}/bot/{botId}/deactivate/', name: 'dev_bot_deactivate', methods: ['GET'])]
    public function botDeactivate(Project $project, int $botId): RedirectResponse
    {
        $this->botService->updateStatus($botId, $project->getId(), false);

        return new RedirectResponse("/admin/projects/{$project->getId()}/dashboard/");
    }

    /**
     * @throws Exception
     */
    #[Route('/dev/project/{project}/bot/{botId}/webhook/activate/', name: 'dev_bot_webhook_activate', methods: ['GET'])]
    public function botWebhookActivate(Project $project, int $botId): RedirectResponse
    {
        $bot = $this->botService->findOne($botId, $project->getId());

        if (!$bot) {
            throw new Exception('Бота не существует');
        }

        $this->eventDispatcher->dispatch(new InitWebhookBotEvent($bot));

        return new RedirectResponse("/admin/projects/{$project->getId()}/dashboard/");
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    #[Route('/dev/project/{project}/bot/{bot}/apply-scenario/', name: 'apply_scenario_to_bot', methods: ['POST'])]
    public function applyScenarioToBot(
        Request $request,
        Project $project,
        Bot $bot,
    ): RedirectResponse {
        $scenarioId = $request->request->get('scenario') ?? throw new Exception('Сценарий не выбран');

        $scenarioTemplate = $this->scenarioTemplateRepository->find($scenarioId);

        $scenarios = $this->serializer->denormalize(
            [
                'scenarios' => $scenarioTemplate->getScenario(),
            ],
            ScenarioCollection::class
        );

        $this->settingConverter->convert($scenarios, $bot);

        return new RedirectResponse("/admin/projects/{$project->getId()}/dashboard/");
    }
}

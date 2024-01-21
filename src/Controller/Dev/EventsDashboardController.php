<?php

namespace App\Controller\Dev;

use App\Converter\SettingConverter;
use App\Entity\Scenario\ScenarioTemplate;
use App\Entity\User\Project;
use App\Event\InitWebhookBotEvent;
use App\Service\Admin\Bot\BotServiceInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Exception;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class EventsDashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly BotServiceInterface $botService,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly KernelInterface $kernel,
        private readonly SettingConverter $settingConverter,
    ) {
    }

    #[Route('/dev/project/{project}/bot/{botId}/activate/', name: 'dev_bot_activate', methods: ['GET'])]
    public function botActivate(Project $project, int $botId): RedirectResponse
    {
        $this->botService->updateStatus($botId, $project->getId(), true);

        return new RedirectResponse('/admin');
    }

    #[Route('/dev/project/{project}/bot/{botId}/deactivate/', name: 'dev_bot_deactivate', methods: ['GET'])]
    public function botDeActivate(Project $project, int $botId): RedirectResponse
    {
        $this->botService->updateStatus($botId, $project->getId(), false);

        return new RedirectResponse('/admin');
    }

    #[Route('/dev/project/{project}/bot/{botId}/webhook/activate/', name: 'dev_bot_webhook_activate', methods: ['GET'])]
    public function botWebhookActivate(Project $project, int $botId): RedirectResponse
    {
        $bot = $this->botService->findOne($botId, $project->getId());

        if (!$bot) {
            throw new Exception('Бота не существует');
        }

        $this->eventDispatcher->dispatch(new InitWebhookBotEvent($bot));

        return new RedirectResponse('/admin');
    }

    /**
     * @throws Exception
     */
    #[Route('/dev/project/{project}/scenario/{scenarioTemplate}/apply/', name: '???', methods: ['GET'])]
    public function applyScenarioToBot(
        Request $request,
        Project $project,
        ScenarioTemplate $scenarioTemplate,
    ): RedirectResponse {
        $botId = $request->query->get('botId') ?? throw new Exception('Нет параметра botId');

        $this->settingConverter->convert([$scenarioTemplate->getScenario()], $project->getId(), $botId);

        return new RedirectResponse('/admin');
    }

    /**
     * @throws Exception
     */
    #[Route('/dev/project/{project}/command/{command}/start/', name: 'dev_command_start', methods: ['GET'])]
    public function commandStart(Project $project, string $command): RedirectResponse
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(
            [
                'command' => $command,
            ]
        );

        $application->run($input);

        return new RedirectResponse('/admin');
    }
}

<?php

namespace App\Controller\Admin\Bot;

use App\Entity\User\Project;
use App\Service\Admin\Bot\BotServiceInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Bot')]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Если получилось удалить возвращаем 204',
)]
class RemoveController extends AbstractController
{
    public function __construct(
        private readonly BotServiceInterface $botService,
    ) {
    }

    #[Route('/api/admin/project/{project}/bot/{botId}/', name: 'admin_bot_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $botId): JsonResponse
    {
        $this->botService->remove($botId, $project->getId());

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}

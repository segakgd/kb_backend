<?php

declare(strict_types=1);

namespace App\Controller\Admin\Bot;

use App\Controller\Admin\Bot\Response\BotResponse;
use App\Entity\User\Project;
use App\Service\Common\Bot\BotServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Получение коллекции ботов',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: BotResponse::class
            )
        )
    ),
)]
#[OA\Tag(name: 'Bot')]
class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly BotServiceInterface $botService,
    ) {}

    #[Route('/api/admin/project/{project}/bot/', name: 'admin_bot_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        try {
            $bots = $this->botService->findAll($project->getId());

            return $this->json(BotResponse::mapFromCollection($bots));
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}

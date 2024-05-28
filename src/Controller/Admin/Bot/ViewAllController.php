<?php

declare(strict_types=1);

namespace App\Controller\Admin\Bot;

use App\Controller\Admin\Bot\DTO\Response\BotResDto;
use App\Controller\Admin\Bot\Response\BotViewAllResponse;
use App\Entity\User\Project;
use App\Service\Admin\Bot\BotServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;
use Throwable;

#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Получение колекции ботов',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: BotResDto::class
            )
        )
    ),
)]
#[OA\Tag(name: 'Bot')]
class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly BotServiceInterface $botService,
    ) {
    }

    #[Route('/api/admin/project/{project}/bot/', name: 'admin_bot_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        try {
            $bots = $this->botService->findAll($project->getId());

            return $this->json($this->serializer->normalize(
                (new BotViewAllResponse())->mapToResponse($bots)
            ));
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}

<?php

namespace App\Controller\Admin\Bot;

use App\Controller\Admin\Bot\DTO\Response\BotResponse;
use App\Controller\Admin\Bot\Exception\NotFoundBotForProjectException;
use App\Controller\Admin\Bot\Response\BotViewOneResponse;
use App\Entity\User\Bot;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

#[OA\Tag(name: 'Bot')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает продукт',
    content: new Model(
        type: BotResponse::class
    ),
)]
class ViewOneController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {}

    /** Получение бота */
    #[Route('/api/admin/project/{project}/bot/{bot}/', name: 'admin_bot_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, Bot $bot): JsonResponse
    {
        try {
            if ($bot->getProjectId() !== $project->getId()) {
                throw new NotFoundBotForProjectException();
            }

            return $this->json(
                $this->serializer->normalize(
                    (new BotViewOneResponse())->mapToResponse($bot)
                )
            );
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}

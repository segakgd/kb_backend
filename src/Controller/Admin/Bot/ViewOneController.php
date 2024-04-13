<?php

namespace App\Controller\Admin\Bot;

use App\Controller\Admin\Bot\DTO\Response\BotResDto;
use App\Entity\User\Bot;
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

#[OA\Tag(name: 'Bot')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает продукт',
    content: new Model(
        type: BotResDto::class
    ),
)]
class ViewOneController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly BotServiceInterface $botService,
    ) {
    }

    /** Получение бота */
    #[Route('/api/admin/project/{project}/bot/{botId}/', name: 'admin_bot_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $botId): JsonResponse
    {
        $bot = $this->botService->findOne($botId, $project->getId());

        $response = $this->mapToResponse($bot);

        return new JsonResponse(
            $this->serializer->normalize(
                $response
            )
        );
    }

    private function mapToResponse(Bot $bot): BotResDto
    {
        return (new BotResDto())
            ->setId($bot->getId())
            ->setName($bot->getName())
            ->setType($bot->getType())
        ;
    }
}

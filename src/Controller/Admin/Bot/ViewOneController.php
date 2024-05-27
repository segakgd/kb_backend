<?php

namespace App\Controller\Admin\Bot;

use App\Controller\Admin\Bot\DTO\Response\BotResDto;
use App\Entity\User\Bot;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;
use Throwable;

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
    ) {
    }

    /** Получение бота */
    #[Route('/api/admin/project/{project}/bot/{bot}/', name: 'admin_bot_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, Bot $bot): JsonResponse
    {
        try {
            $response = $this->mapToResponse($bot);

            return new JsonResponse(
                $this->serializer->normalize(
                    $response
                )
            );
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
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

<?php

namespace App\Controller\Admin\Bot;

use App\Controller\Admin\Bot\Request\BotRequest;
use App\Controller\Admin\Bot\Response\BotResponse;
use App\Controller\GeneralAbstractController;
use App\Entity\User\Project;
use App\Service\Common\Bot\BotServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

#[OA\Tag(name: 'Bot')]
#[OA\RequestBody(
    content: new Model(
        type: BotRequest::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Возвращает 204 при создании',
)]
class CreateController extends GeneralAbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly BotServiceInterface $botService,
    ) {
        parent::__construct(
            $this->serializer,
            $this->validator,
        );
    }

    /** Создание бота */
    #[Route('/api/admin/project/{project}/bot/', name: 'admin_bot_add', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        try {
            $requestDto = $this->getValidDtoFromRequest($request, BotRequest::class);

            $bot = $this->botService->add($requestDto, $project->getId());

            return $this->json(BotResponse::mapFromEntity($bot));
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}

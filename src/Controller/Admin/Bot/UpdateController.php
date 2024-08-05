<?php

namespace App\Controller\Admin\Bot;

use App\Controller\Admin\Bot\DTO\Request\UpdateBotReqDto;
use App\Controller\Admin\Bot\Response\BotUpdateResponse;
use App\Controller\GeneralAbstractController;
use App\Entity\User\Bot;
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
        type: UpdateBotReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Возвращает 204 при создании',
)]
class UpdateController extends GeneralAbstractController
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

    /** Обновление одного продукта */
    #[Route('/api/admin/project/{project}/bot/{bot}/', name: 'admin_bot_update', methods: ['PATCH'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, Bot $bot): JsonResponse
    {
        try {
            $requestDto = $this->getValidDtoFromRequest($request, UpdateBotReqDto::class);

            $bot = $this->botService->update($requestDto, $bot->getId(), $project->getId());

            return $this->json(
                $this->serializer->normalize(
                    (new BotUpdateResponse())->mapToResponse($bot)
                )
            );
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}

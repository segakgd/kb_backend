<?php

namespace App\Controller\Admin\Bot;

use App\Controller\Admin\Bot\Request\InitBotRequest;
use App\Controller\GeneralAbstractController;
use App\Entity\User\Project;
use App\Service\Common\Bot\BotServiceInterface;
use Exception;
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
        type: InitBotRequest::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Возвращает 204 при создании',
)]
class InitController extends GeneralAbstractController
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

    /**
     * @throws Exception
     */
    /** Инициализация бота */
    #[Route('/api/admin/project/{project}/bot/{botId}/init/', name: 'admin_bot_init', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, int $botId): JsonResponse
    {
        try {
            $requestDto = $this->getValidDtoFromRequest($request, InitBotRequest::class);

            $this->botService->init($requestDto, $botId, $project->getId());

            return $this->json(
                [],
                Response::HTTP_NO_CONTENT
            );
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}

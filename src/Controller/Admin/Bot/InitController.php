<?php

namespace App\Controller\Admin\Bot;

use App\Controller\Admin\Bot\DTO\Request\InitBotReqDto;
use App\Entity\User\Project;
use App\Service\Admin\Bot\BotServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Bot')]
#[OA\RequestBody(
    content: new Model(
        type: InitBotReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Возвращает 204 при создании',
)]
class InitController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly BotServiceInterface $botService,
    ) {
    }

    /**
     * @throws Exception
     */
    /** Инициализация бота */
    #[Route('/api/admin/project/{project}/bot/{botId}/init/', name: 'admin_bot_init', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, int $botId): JsonResponse
    {
        $content = $request->getContent();

        $requestDto = $this->serializer->deserialize($content, InitBotReqDto::class, 'json');

        $errors = $this->validator->validate($requestDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $this->botService->init($requestDto, $botId, $project->getId());

        return new JsonResponse(
            [],
            Response::HTTP_NO_CONTENT
        );
    }
}

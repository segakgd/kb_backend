<?php

namespace App\Controller\Admin\Bot;

use App\Controller\Admin\Bot\DTO\Request\UpdateBotReqDto;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[OA\Tag(name: 'Product')]
#[OA\RequestBody(
    content: new Model(
        type: UpdateBotReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Возвращает 204 при создании',
)]
class UpdateController extends AbstractController
{

    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
    ) {
    }

    /** Обновление одного продукта */
    #[Route('/api/admin/project/{project}/bot/{botId}/', name: 'admin_bot_update', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, int $botId): JsonResponse
    {
        $content = $request->getContent();

        $requestDto = $this->serializer->deserialize($content, UpdateBotReqDto::class, 'json');

        $errors = $this->validator->validate($requestDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        // todo ... тут мы должны обратиться к сервису или менеджеру ...

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Controller\Admin\Bot;

use App\Controller\Admin\Bot\DTO\Request\UpdateBotReqDto;
use App\Controller\Admin\Bot\DTO\Response\BotResDto;
use App\Entity\User\Bot;
use App\Entity\User\Project;
use App\Service\Admin\Bot\BotServiceInterface;
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
        private readonly BotServiceInterface $botService,
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

        $bot = $this->botService->update($requestDto, $botId, $project->getId());

        $response = $this->mapToResponse($bot);

        return new JsonResponse(
            $this->serializer->normalize($response)
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

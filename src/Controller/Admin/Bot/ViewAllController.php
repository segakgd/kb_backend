<?php

declare(strict_types=1);

namespace App\Controller\Admin\Bot;

use App\Controller\Admin\Bot\Request\BotSearchRequest;
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
use Throwable;

#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Получение коллекции ботов',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: BotResponse::class
            )
        )
    ),
)]
#[OA\Tag(name: 'Bot')]
class ViewAllController extends GeneralAbstractController
{
    #[Route('/api/admin/project/{project}/bot/', name: 'admin_bot_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(
        Request $request,
        Project $project,
        BotServiceInterface $botService,
    ): JsonResponse {
        try {
            $requestDto = $this->getValidDtoFromFormDataRequest($request, BotSearchRequest::class);

            $botsCollection = $botService->search($project, $requestDto);

            $botResponse = BotResponse::mapCollection($botsCollection->getItems());

            return $this->json(
                static::makePaginateResponse(
                    $botResponse,
                    $botsCollection->getPaginate()
                )
            );
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}

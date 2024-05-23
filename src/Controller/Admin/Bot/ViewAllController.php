<?php

declare(strict_types=1);

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

#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает коллекцию товаров',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: BotResDto::class
            )
        )
    ),
)]
#[OA\Tag(name: 'Bot')]
class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly BotServiceInterface $botService,
    ) {
    }

    /** Получение колекции ботов */
    #[Route('/api/admin/project/{project}/bot/', name: 'admin_bot_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        dd('asd');
        $bots = $this->botService->findAll($project->getId());

        return $this->json($this->serializer->normalize($this->mapToResponse($bots)));
    }

    private function mapToResponse(array $bots): array
    {
        $result = [];

        /** @var Bot $bot */
        foreach ($bots as $bot) {
            $result[] = (new BotResDto())
                ->setId($bot->getId())
                ->setName($bot->getName())
                ->setType($bot->getType());
        }

        return $result;
    }
}

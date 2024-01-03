<?php

namespace App\Controller\Admin\Bot;

use App\Controller\Admin\Bot\DTO\Response\BotResDto;
use App\Entity\User\Bot;
use App\Entity\User\Project;
use App\Service\Admin\Bot\BotServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly BotServiceInterface $botService,
    ) {
    }

    #[Route('/api/admin/project/{project}/bot/', name: 'admin_bot_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        $bots = $this->botService->findAll($project->getId());

        $response = $this->mapToResponse($bots);

        return new JsonResponse(
            $this->serializer->normalize($response)
        );
    }

    private function mapToResponse(array $bots): array
    {
        $result = [];

        /** @var Bot $bot */
        foreach ($bots as $bot){

            $result[] = (new BotResDto())
                ->setId($bot->getId())
                ->setName($bot->getName())
                ->setType($bot->getType())
            ;
        }

        return $result;
    }
}

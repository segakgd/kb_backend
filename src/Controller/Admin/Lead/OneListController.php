<?php

namespace App\Controller\Admin\Lead;

use App\Dto\Order\LeadFullRespDto;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Lead')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает коллекцию заявок',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: LeadFullRespDto::class
            )
        )
    ),
)]
class OneListController extends AbstractController
{
    #[OA\Tag(name: 'Lead')]
    #[Route('/api/admin/project/{project}/lead/{leadId}/', name: 'admin_lead_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $leadId): JsonResponse
    {
        return new JsonResponse();
    }
}

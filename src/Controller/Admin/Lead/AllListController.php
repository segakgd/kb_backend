<?php

namespace App\Controller\Admin\Lead;

use App\Dto\All\FilterLeadReqDto;
use App\Dto\All\LeadRespDto;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Lead')]
#[OA\RequestBody(
    content: new Model(
        type: FilterLeadReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает коллекцию заявок',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: LeadRespDto::class
            )
        )
    ),
)]
class AllListController extends AbstractController
{
    #[Route('/api/admin/project/{project}/lead/', name: 'admin_lead_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        return new JsonResponse(
            [
                new LeadRespDto(),
                new LeadRespDto(),
            ]
        );
    }
}

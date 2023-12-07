<?php

namespace App\Controller\Admin\Lead;

use App\Dto\Admin\Lead\Request\LeadReqDto;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Lead')]
#[OA\RequestBody(
    content: new Model(
        type: LeadReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Возвращает 204 при создании',
)]
class CreateController extends AbstractController
{
    #[Route('/api/admin/project/{project}/lead/', name: 'admin_lead_create', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        return new JsonResponse('', 204);
    }
}

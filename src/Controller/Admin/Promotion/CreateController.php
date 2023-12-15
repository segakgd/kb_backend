<?php

namespace App\Controller\Admin\Promotion;

use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Promotion')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: '', // todo You need to write a description
//    content: new OA\JsonContent(
//        type: 'array',
//        items: new OA\Items(
//            ref: new Model(
//                type: ProjectRespDto::class
//            )
//        )
//    ),
)]
class CreateController extends AbstractController
{
    #[Route('/api/admin/project/{project}/promotion/', name: 'admin_promotion_create', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        return new JsonResponse();
    }
}

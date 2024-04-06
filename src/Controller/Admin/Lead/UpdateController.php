<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead;

use App\Entity\User\Project;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UpdateController extends AbstractController
{
    #[OA\Tag(name: 'Lead')]
    #[Route('/api/admin/project/{project}/lead/{lead}/', name: 'admin_lead_update', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, int $lead): JsonResponse
    {
        // todo ... тут мы должны обратиться к сервису или менеджеру ...

        return new JsonResponse([], Response::HTTP_NO_CONTENT); // todo пока что оставил, позже сделаем этот функционал
    }
}

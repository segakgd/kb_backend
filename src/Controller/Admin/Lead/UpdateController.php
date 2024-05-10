<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead;

use App\Controller\Admin\Lead\DTO\Request\LeadReqDto;
use App\Entity\Lead\Deal;
use App\Entity\User\Project;
use App\Service\Admin\Lead\LeadManager;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

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
class UpdateController extends AbstractController
{
    public function __construct(
        private readonly LeadManager $leadManager,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator
    ) {
    }

    /** Обновление лида */
    #[OA\Tag(name: 'Lead')]
    #[Route('/api/admin/project/{project}/lead/{lead}/', name: 'admin_lead_update', methods: ['PATCH'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, ?Deal $lead): JsonResponse
    {
        if (null === $lead) {
            return $this->json('Not found', Response::HTTP_NOT_FOUND);
        }

        if ($lead->getProjectId() !== $project->getId()) {
            throw new AccessDeniedException('Access Denied.');
        }

        try {
            $requestDto = $this->serializer->deserialize($request->getContent(), LeadReqDto::class, 'json');

            $errors = $this->validator->validate($requestDto);

            if (count($errors) > 0) {
                return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
            }

            $this->leadManager->update($requestDto, $lead, $project);
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return $this->json([], Response::HTTP_NO_CONTENT); // todo пока что оставил, позже сделаем этот функционал
    }
}

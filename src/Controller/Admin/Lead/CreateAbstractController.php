<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead;

use App\Controller\Admin\Lead\DTO\Request\LeadReqDto;
use App\Controller\GeneralAbstractController;
use App\Entity\User\Project;
use App\Service\Admin\Lead\LeadManager;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

#[OA\Tag(name: 'Lead')]
#[OA\RequestBody(
    description: 'Создание лида', content: new Model(
        type: LeadReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Возвращает 204 при создании',
)]
class CreateAbstractController extends GeneralAbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly LeadManager $leadManager,
    ) {
        parent::__construct(
            $this->serializer,
            $this->validator,
        );
    }

    /**
     * Создание лида
     */
    #[Route('/api/admin/project/{project}/lead/', name: 'admin_lead_create', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        try {
            $requestDto = $this->getValidDtoFromRequest($request, LeadReqDto::class);

            $this->leadManager->create($requestDto, $project);

            return $this->json([], Response::HTTP_NO_CONTENT);
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}

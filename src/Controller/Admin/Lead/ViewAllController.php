<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead;

use App\Controller\Admin\Lead\DTO\Request\FilterLeadsReqDto;
use App\Controller\Admin\Lead\DTO\Response\AllLeadRespDto;
use App\Entity\User\Project;
use App\Service\Admin\Lead\LeadManager;
use App\Service\Admin\Lead\LeadMapper;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Lead')]
#[OA\RequestBody(
    content: new Model(
        type: FilterLeadsReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает коллекцию заявок',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: AllLeadRespDto::class
            )
        )
    ),
)]
class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly LeadManager $leadManager,
        private readonly LeadMapper $leadMapper,
    ) {
    }

    /** Получение коллекцию лидов */
    #[Route('/api/admin/project/{project}/lead/', name: 'admin_lead_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        $content = $request->getContent();

        $requestDto = $this->serializer->deserialize($content, FilterLeadsReqDto::class, 'json');

        $errors = $this->validator->validate($requestDto); // todo -> пока не нашел применения

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $leads = $this->leadManager->getAllByProject($project);

        $response = [];

        foreach ($leads as $lead) {
            $response[] = $this->leadMapper->mapToResponse($lead);
        }

        return $this->json($response);
    }
}

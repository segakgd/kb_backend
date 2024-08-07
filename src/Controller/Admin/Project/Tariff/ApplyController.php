<?php

namespace App\Controller\Admin\Project\Tariff;

use App\Controller\Admin\Project\DTO\Request\TariffSettingReqDto;
use App\Controller\GeneralAbstractController;
use App\Entity\User\Project;
use App\Service\Common\Project\TariffServiceInterface;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Tariff')]
#[OA\RequestBody(
    content: new Model(
        type: TariffSettingReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Возвращает 204 если новый тариф применён',
)]
class ApplyController extends GeneralAbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly TariffServiceInterface $tariffService,
    ) {
        parent::__construct(
            $this->serializer,
            $this->validator,
        );
    }

    /**
     * @throws Exception
     */
    #[Route('/api/admin/project/{project}/setting/tariff/', name: 'admin_project_update_tariff', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        $requestDto = $this->getValidDtoFromRequest($request, TariffSettingReqDto::class);

        $isApply = $this->tariffService->applyTariff($project, $requestDto->getCode());

        if (!$isApply) {
            return $this->json('Тариф не применился', Response::HTTP_CONFLICT);
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Controller\Admin\Project\Setting;

use App\Controller\Admin\Project\DTO\Response\Setting\ProjectSettingRespDto;
use App\Controller\Admin\Project\Request\ProjectSettingRequest;
use App\Controller\Admin\Project\Response\Setting\UpdateSettingResponse;
use App\Controller\GeneralAbstractController;
use App\Entity\User\Project;
use App\Service\Common\Project\ProjectSettingServiceInterface;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Project')]
#[OA\RequestBody(
    description: 'Обновление настроек',
    content: new Model(
        type: ProjectSettingRequest::class,
    )
)]
class UpdateController extends GeneralAbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly ProjectSettingServiceInterface $projectSettingService,
    ) {
        parent::__construct(
            $this->serializer,
            $this->validator,
        );
    }

    /**
     * @throws Exception
     */
    #[Route('/api/admin/project/{project}/setting/', name: 'admin_project_setting_update', methods: ['PATCH'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        $requestDto = $this->getValidDtoFromRequest($request, ProjectSettingRequest::class);

        $projectSetting = $this->projectSettingService->updateSetting($project->getId(), $requestDto);

        return $this->json(
            ProjectSettingRespDto::mapFromEntity($projectSetting)
        );
    }
}

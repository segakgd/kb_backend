<?php

namespace App\Controller\Admin\Project\Setting;

use App\Controller\Admin\Project\DTO\Response\Setting\ProjectSettingRespDto;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Project')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает нестройки',
    content: new Model(
        type: ProjectSettingRespDto::class,
    )
)]
class AllListController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route('/api/admin/projects/{project}/setting', name: 'admin_list_project_setting', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        $fakeProjectSetting = (new ProjectSettingRespDto())
            ->setMainSettings()
            ->setNotificationSetting()
        ;

        return new JsonResponse(
            $this->serializer->normalize(
                $fakeProjectSetting,
            )
        );
    }
}

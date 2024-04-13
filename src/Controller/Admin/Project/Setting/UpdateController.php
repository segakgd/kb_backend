<?php

namespace App\Controller\Admin\Project\Setting;

use App\Controller\Admin\Project\DTO\Request\ProjectSettingReqDto;
use App\Controller\Admin\Project\DTO\Response\Setting\ProjectMainSettingRespDto;
use App\Controller\Admin\Project\DTO\Response\Setting\ProjectNotificationSettingRespDto;
use App\Controller\Admin\Project\DTO\Response\Setting\ProjectNotificationsSettingRespDto;
use App\Controller\Admin\Project\DTO\Response\Setting\ProjectSettingRespDto;
use App\Entity\User\Project;
use App\Entity\User\ProjectSetting;
use App\Service\Common\Project\ProjectSettingServiceInterface;
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

#[OA\Tag(name: 'Project')]
#[OA\RequestBody(
    content: new Model(
        type: ProjectSettingReqDto::class,
    )
)]
class UpdateController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly ProjectSettingServiceInterface $projectSettingService,
    ) {
    }

    /** Обновление настроек */
    #[Route('/api/admin/project/{project}/setting/', name: 'admin_project_update', methods: ['PATCH'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        $content = $request->getContent();

        $requestDto = $this->serializer->deserialize($content, ProjectSettingReqDto::class, 'json');

        $errors = $this->validator->validate($requestDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $projectSetting = $this->projectSettingService->updateSetting($project->getId(), $requestDto);

        return new JsonResponse(
            $this->serializer->normalize(
                $this->mapToResponse($projectSetting),
            )
        );
    }

    private function mapToResponse(ProjectSetting $projectSetting): ProjectSettingRespDto
    {
        $basic = $projectSetting->getBasic();
        $notifications = $projectSetting->getNotification(); // todo превратить в dto а то ниже ад

        $aboutNewLead = $notifications['aboutNewLead'] ?? null;
        $aboutChangesStatusLead = $notifications['aboutChangesStatusLead'] ?? null;

        $fakeNotificationAboutNewLead = (new ProjectNotificationSettingRespDto())
            ->setSystem($aboutNewLead['system'] ?? false)
            ->setMail($aboutNewLead['mail'] ?? false)
            ->setSms($aboutNewLead['sms'] ?? false)
            ->setTelegram($aboutNewLead['telegram'] ?? false)
        ;

        $fakeNotificationAboutChangesStatusLead = (new ProjectNotificationSettingRespDto())
            ->setSystem($aboutChangesStatusLead['system'] ?? false)
            ->setMail($aboutChangesStatusLead['mail'] ?? false)
            ->setSms($aboutChangesStatusLead['sms'] ?? false)
            ->setTelegram($aboutChangesStatusLead['telegram'] ?? false)
        ;

        $fakeNotificationSetting = (new ProjectNotificationsSettingRespDto())
            ->setNewLead($fakeNotificationAboutNewLead)
            ->setChangesStatusLead($fakeNotificationAboutChangesStatusLead)
        ;

        $fakeMainSetting = (new ProjectMainSettingRespDto())
            ->setCountry($basic['country'] ?? 'russia')
            ->setLanguage($basic['language'] ?? 'ru')
            ->setTimeZone($basic['timeZone'] ?? 'Europe/Moscow')
            ->setCurrency($basic['currency'] ?? 'RUB')
        ;

        return (new ProjectSettingRespDto())
            ->setId($projectSetting->getId())
            ->setMainSettings($fakeMainSetting)
            ->setNotificationSetting($fakeNotificationSetting)
            ;
    }
}

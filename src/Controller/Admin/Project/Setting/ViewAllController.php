<?php

namespace App\Controller\Admin\Project\Setting;

use App\Controller\Admin\Project\DTO\Response\ProjectTariffSettingRespDto;
use App\Controller\Admin\Project\DTO\Response\Setting\ProjectMainSettingRespDto;
use App\Controller\Admin\Project\DTO\Response\Setting\ProjectNotificationSettingRespDto;
use App\Controller\Admin\Project\DTO\Response\Setting\ProjectNotificationsSettingRespDto;
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

#[OA\Tag(name: 'Project')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает нестройки',
    content: new Model(
        type: ProjectSettingRespDto::class,
    )
)]
class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route('/api/admin/projects/{project}/setting', name: 'admin_list_project_setting', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        $fakeNotificationAboutNewLead = (new ProjectNotificationSettingRespDto())
            ->setMail(true)
            ->setSms(true)
            ->setTelegram(false)
        ;

        $fakeNotificationAboutChangesStatusLead = (new ProjectNotificationSettingRespDto())
            ->setMail(true)
            ->setSms(true)
        ;

        $fakeNotificationSetting = (new ProjectNotificationsSettingRespDto())
            ->setNewLead($fakeNotificationAboutNewLead)
            ->setChangesStatusLead($fakeNotificationAboutChangesStatusLead)
        ;

        $fakeTariffSetting = (new ProjectTariffSettingRespDto())
            ->setName('Самый лучший тариф')
            ->setPrice(100000)
            ->setPriceWF('1000,00')
        ;

        $fakeMainSetting = (new ProjectMainSettingRespDto())
            ->setName('Мой первый проект')
            ->setCountry('russia')
            ->setLanguage('ru')
            ->setTariff($fakeTariffSetting)
            ->setTimeZone('Europe/Moscow')
            ->setCurrency('RUB')
        ;

        $fakeProjectSetting = (new ProjectSettingRespDto())
            ->setMainSettings($fakeMainSetting)
            ->setNotificationSetting($fakeNotificationSetting)
        ;

        return new JsonResponse(
            $this->serializer->normalize(
                $fakeProjectSetting,
            )
        );
    }
}

<?php

namespace App\Controller\Admin\Project\Setting;

use App\Controller\Admin\Project\DTO\Response\ProjectTariffSettingRespDto;
use App\Controller\Admin\Project\DTO\Response\Setting\ProjectMainSettingRespDto;
use App\Controller\Admin\Project\DTO\Response\Setting\ProjectNotificationSettingRespDto;
use App\Controller\Admin\Project\DTO\Response\Setting\ProjectNotificationsSettingRespDto;
use App\Controller\Admin\Project\DTO\Response\Setting\ProjectSettingRespDto;
use App\Entity\User\Project;
use App\Entity\User\ProjectSetting;
use App\Entity\User\Tariff;
use App\Service\Common\Project\ProjectSettingServiceInterface;
use App\Service\Common\Project\TariffServiceInterface;
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
        private readonly SerializerInterface $serializer,
        private readonly ProjectSettingServiceInterface $projectSettingService,
        private readonly TariffServiceInterface $tariffService,
    ) {
    }

    #[Route('/api/admin/project/{project}/setting/', name: 'admin_list_project_setting', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        $projectSetting = $this->projectSettingService->getSettingForProject($project->getId());

        $tariffId = $projectSetting->getTariffId();

        $tariff = $this->tariffService->getTariffById($tariffId);

        return new JsonResponse(
            $this->serializer->normalize(
                $this->mapToResponse($projectSetting, $tariff),
            )
        );
    }

    private function mapToResponse(ProjectSetting $projectSetting, Tariff $tariff): ProjectSettingRespDto // todo убрать в маппер
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

        $tariffSetting = (new ProjectTariffSettingRespDto())
            ->setName($tariff->getName())
            ->setPrice($tariff->getPrice())
            ->setPriceWF($tariff->getPriceWF())
        ;

        $fakeMainSetting = (new ProjectMainSettingRespDto())
            ->setCountry($basic['country'] ?? 'russia')
            ->setLanguage($basic['language'] ?? 'ru')
            ->setTariff($tariffSetting)
            ->setTimeZone($basic['timeZone'] ?? 'Europe/Moscow')
            ->setCurrency($basic['currency'] ?? 'RUB')
        ;

        return (new ProjectSettingRespDto())
            ->setMainSettings($fakeMainSetting)
            ->setNotificationSetting($fakeNotificationSetting)
        ;
    }
}

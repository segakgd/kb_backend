<?php

namespace App\Controller\Admin\Project;

use App\Controller\Admin\Project\DTO\Response\ProjectRespDto;
use App\Controller\Admin\Project\DTO\Response\Statistic\ProjectStatisticRespDto;
use App\Controller\Admin\Project\DTO\Response\Statistic\ProjectStatisticsRespDto;
use DateTimeImmutable;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Project')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает коллекцию проектов',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: ProjectRespDto::class
            )
        )
    ),
)]
class ViewAllController extends AbstractController
{
    #[Route('/api/admin/projects/', name: 'admin_project_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(): JsonResponse
    {
        $fakeStatistic = (new ProjectStatisticRespDto())
            ->setCount(13)
        ;

        $fakeStatistics = (new ProjectStatisticsRespDto())
            ->setChats($fakeStatistic)
            ->setLead($fakeStatistic)
            ->setBooking($fakeStatistic)
        ;

        $fakeProject = (new ProjectRespDto())
            ->setName('Название проекта')
            ->setStatus('active')
            ->setStatistic($fakeStatistics)
            ->setActiveFrom(new DateTimeImmutable())
            ->setActiveTo(new DateTimeImmutable())
        ;

        return new JsonResponse(
            [
                $fakeProject,
                $fakeProject,
            ]
        );
    }
}

<?php

namespace App\Controller\Admin\Project\Tariff;

use App\Controller\Admin\Project\DTO\Response\TariffSettingRespDto;
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
    description: 'Возвращает коллекцию доступных тарифов',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: TariffSettingRespDto::class
            )
        )
    ),
)]
class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route('/api/admin/project/{project}/setting/tariff/', name: 'admin_project_list_tariff', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        $fakeTariff = (new TariffSettingRespDto())
            ->setName('Название тарифа')
            ->setPrice(100000)
            ->setPriceWF('1000,00')
            ->setDescription('Какое-то описание тарифа ')
            ->setCode('CODE_2024')
            ->setActive(true)
        ;

        return new JsonResponse(
            $this->serializer->normalize(
                [
                    $fakeTariff,
                    $fakeTariff,
                ]
            )
        );
    }
}

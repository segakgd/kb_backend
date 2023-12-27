<?php

namespace App\Controller\Admin\Project\Tariff;

use App\Controller\Admin\Project\DTO\Response\TariffSettingRespDto;
use App\Entity\User\Project;
use App\Entity\User\Tariff;
use App\Service\Common\Project\TariffServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[OA\Tag(name: 'Tariff')]
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
        private readonly SerializerInterface $serializer,
        private readonly TariffServiceInterface $tariffService,
    ) {
    }

    #[Route('/api/admin/tariffs/', name: 'admin_list_tariffs', methods: ['GET'])]
    public function execute(): JsonResponse
    {
        // todo проверить что пользователь в систему зашёл. На право на лево не стоит раскидываться апихами

        $tariffs = $this->tariffService->getAllTariff();

        return new JsonResponse(
            $this->serializer->normalize($this->mapToResponse($tariffs))
        );
    }

    private function mapToResponse(array $tariffs): array
    {
        $result = [];

        /** @var Tariff $tariff */
        foreach ($tariffs as $tariff){
            $result[] = (new TariffSettingRespDto())
                ->setName($tariff->getName())
                ->setPrice($tariff->getPrice())
                ->setPriceWF($tariff->getPriceWF())
                ->setDescription($tariff->getDescription())
                ->setCode($tariff->getCode())
                ->setActive($tariff->isActive())
            ;
        }

        return $result;
    }

}

<?php

declare(strict_types=1);

namespace App\Controller\Admin\Project\Tariff;

use App\Controller\Admin\Project\DTO\Response\TariffSettingRespDto;
use App\Service\Common\Project\TariffServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
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
    ) {}

    #[Route('/api/admin/tariffs/', name: 'admin_list_tariffs', methods: ['GET'])]
    public function execute(): JsonResponse
    {
        if (!$this->getUser()) {
            throw new AccessDeniedException('Access Denied.');
        }

        $tariffs = $this->tariffService->getAllTariff();

        return $this->json($this->serializer->normalize(
            TariffSettingRespDto::mapCollection($tariffs)
        ));
    }
}

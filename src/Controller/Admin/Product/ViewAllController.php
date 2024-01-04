<?php

namespace App\Controller\Admin\Product;

use App\Controller\Admin\Product\DTO\Response\AllProductRespDto;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает коллекцию товаров',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: AllProductRespDto::class
            )
        )
    ),
)]
#[OA\Tag(name: 'Product')]
class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    /** Получение всех продуктов */
    #[Route('/api/admin/project/{project}/product/', name: 'admin_product_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        // todo ... тут мы должны обратиться к сервису или менеджеру ...

        $product = (new AllProductRespDto())
            ->setId(111)
            ->setName('Продукт')
            ->setType('service')
            ->setArticle('PRODUCT-1')
            ->setAffordablePrices('100-200')
            ->setVisible(true)
            ->setCount(1)
        ;

        return new JsonResponse(
            $this->serializer->normalize(
                [
                    $product,
                    $product,
                ]
            )
        );
    }
}

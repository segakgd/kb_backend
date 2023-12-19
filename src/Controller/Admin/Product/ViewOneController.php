<?php

namespace App\Controller\Admin\Product;

use App\Controller\Admin\Product\DTO\Response\ProductCategoryRespDto;
use App\Controller\Admin\Product\DTO\Response\ProductRespDto;
use App\Controller\Admin\Lead\DTO\Response\Order\Product\ProductRespDto as LeadProductRespDto;
use App\Controller\Admin\Product\DTO\Response\ProductVariantRespDto;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[OA\Tag(name: 'Product')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает продукт',
    content: new Model(
        type: ProductRespDto::class
    ),
)]
class ViewOneController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
    }

    /** Получение одного продукта */
    #[Route('/api/admin/project/{project}/product/{productId}/', name: 'admin_product_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $productId): JsonResponse
    {
        // todo ... тут мы должны обратиться к сервису или менеджеру ...

        $variant = (new ProductVariantRespDto())
            ->setName('Имя варианта')
            ->setPrice(10000)
            ->setCount(1)
        ;

        $category = (new ProductCategoryRespDto())
            ->setId(111)
            ->setName('Имя категории')
        ;

        $product = (new ProductRespDto)
            ->setId(111)
            ->setName('Продукт')
            ->setType(LeadProductRespDto::TYPE_PRODUCT)
            ->setImage('image.fake')
            ->setArticle('ARTICLE')
            ->setVisible(true)
            ->setDescription('Какое-то описание чего-либо')
            ->addVariants($variant)
            ->addCategory($category)
        ;

        return new JsonResponse(
            $this->serializer->normalize($product)
        );
    }
}

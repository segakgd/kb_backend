<?php

namespace App\Service\Constructor\Actions\Ecommerce;

use App\Entity\Ecommerce\ProductVariant;
use App\Helper\MessageHelper;
use App\Service\Common\Ecommerce\Product\Service\ProductService;
use App\Service\Constructor\Core\Actions\AbstractAction;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;
use Exception;

class VariantsProductAction extends AbstractAction
{
    public static function getName(): string
    {
        return 'variants.product.action';
    }

    public function __construct(
        private readonly ProductService $productService,
    ) {}

    /**
     * @throws Exception
     */
    public function complete(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $content = $responsible->getContent();

        $productId = $responsible->getEvent()->getData()->getProductId();

        $product = $this->productService->find($productId);

        if (null === $product) {
            throw new Exception('Product not found');
        }

        $variants = $product->getVariants();

        $targetVariantId = null;

        foreach ($variants as $variant) {
            if ($variant->getName() === $content) {
                $targetVariantId = $variant->getId();
            }
        }

        if (null === $targetVariantId) {
            throw new Exception('Выбранного варианта не существует!');
        }

        $responsible->getEvent()->getData()->setVariantId($targetVariantId);

        $message = "Вы выбрали вариант: $content. \n\n Укажите количество: ";

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
        );

        $responsible->getResult()->setMessage($responsibleMessage);

        return $responsible;
    }

    public function condition(ResponsibleInterface $responsible): ConditionInterface
    {
        $productId = $responsible->getEvent()->getData()->getProductId();

        $product = $this->productService->find($productId);

        $variants = $product->getVariants();

        $keyBoards = [];

        /** @var ProductVariant $variant */
        foreach ($variants as $variant) {
            $keyBoards[] = [
                'text' => $variant->getName(),
            ];
        }

        return $this->makeCondition(
            [
                $keyBoards,
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function before(ResponsibleInterface $responsible): bool
    {
        return true;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        return true;
    }

    public function after(ResponsibleInterface $responsible): bool
    {
        return true;
    }
}

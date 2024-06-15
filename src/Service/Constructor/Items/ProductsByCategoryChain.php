<?php

namespace App\Service\Constructor\Items;

use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\Common\PaginateService;
use App\Service\Constructor\Core\Chains\AbstractChain;
use App\Service\Constructor\Core\Dto\Condition;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;
use Exception;

class ProductsByCategoryChain extends AbstractChain
{
    public function __construct(
        private readonly ProductService  $productService,
        private readonly PaginateService $paginateService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function success(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $content = $responsible->getCacheDto()->getContent();

        $message = "Допустим что, что-то ещё случилось. Ну, товар ты выбрал или ешё чего. И да, кликнул ты вот это: $content";

        if ('Добавить в корзину' === $content) {
            $productId = $responsible->getCacheDto()->getEvent()->getData()->getProductId();

            $message = "ID продукта: $productId";
        }

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
        );

        $responsible->getResult()->addMessage($responsibleMessage);

        return $responsible;
    }

    public function condition(ResponsibleInterface $responsible): ConditionInterface
    {
        return new Condition();
    }

    /**
     * @throws Exception
     */
    public function validate(ResponsibleInterface $responsible): bool
    {
        $content = $responsible->getCacheDto()->getContent();

        $categoryId = $responsible->getCacheDto()->getEvent()->getData()->getCategoryId();

        if ('Предыдущий' === $content) {
            $data = $responsible->getCacheDto()->getEvent()->getData();

            $products = $this->productService->getProductsByCategory($data->getPageNow(), $categoryId, 'prev');

            $this->paginateService->pug(
                responsible: $responsible,
                products: $products,
                cacheDataDto: $data
            );

            return false;
        }

        if ('Следующий' === $content) {
            $data = $responsible->getCacheDto()->getEvent()->getData();

            $products = $this->productService->getProductsByCategory($data->getPageNow(), $categoryId, 'next');

            $this->paginateService->pug(
                responsible: $responsible,
                products: $products,
                cacheDataDto: $data
            );

            return false;
        }

        $validData = [
            'Предыдущий',
            'Следующий',
            'Добавить в корзину',
            'Вернуться в главное меню',
            'Вернуться к категориям',
        ];

        if (in_array($content, $validData)) {
            return true;
        }

        return false;
    }
}

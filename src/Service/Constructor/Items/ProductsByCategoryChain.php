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
    public function complete(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $content = $responsible->getCacheDto()->getContent();

        $message = "Допустим что, что-то ещё случилось. Ну, товар ты выбрал или ешё чего. И да, кликнул ты вот это: $content";

        if ('Добавить в корзину' === $content) {
            $productId = $responsible->getCacheDto()->getEvent()->getData()->getProductId();

//            $product = $this->productService->find($productId);

//            $variantCounts = $product->getVariants()->count();
//
//            if ($variantCounts === 1) {
//                // todo нет смысла выводить варианты - мб сразу прыгнуть на конец цепи
//            }

            $message = "Выберите один из доступных вариантов: ";
        }

//        if ('Выбрать вариант' === $content) {
//            $message = "Допустим что, что-то ещё случилось. Ну, товар ты выбрал или ешё чего. И да, кликнул ты вот это: $content";
//        }

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
//            keyBoard: $responsible->getNextCondition()->getKeyBoard()
        );

        $responsible->getResult()->setMessage($responsibleMessage);

        return $responsible;
    }

    public function condition(ResponsibleInterface $responsible): ConditionInterface
    {
        return new Condition();
    }

    /**
     * @throws Exception
     */
    public function perform(ResponsibleInterface $responsible): bool
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

        return true;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        return $this->isValid(
            $responsible,
            [
                'Предыдущий',
                'Следующий',
                'Выбрать вариант',
                'Добавить в корзину',
                'Вернуться в главное меню',
                'Вернуться к категориям',
            ]
        );
    }
}

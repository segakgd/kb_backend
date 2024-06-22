<?php

namespace App\Service\Constructor\Items;

use App\Enum\TargetEnum;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\Common\PaginateService;
use App\Service\Constructor\Core\Chains\AbstractChain;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;
use Exception;

class ProductsByCategoryChain extends AbstractChain
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly PaginateService $paginateService,
    ) {}

    /**
     * @throws Exception
     */
    public function complete(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: 'Выберите один из доступных вариантов:',
        );

        $responsible->getResult()->setMessage($responsibleMessage);

        return $responsible;
    }

    public function condition(ResponsibleInterface $responsible): ConditionInterface
    {
        return $this->makeCondition();
    }

    /**
     * @throws Exception
     */
    public function perform(ResponsibleInterface $responsible): bool
    {
        $content = $responsible->getCacheDto()->getContent();

        if ('Добавить в корзину' === $content) {
            return true;
        }

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

        if ('Вернуться в главное меню' === $content) {
            $responsible->setJump(TargetEnum::Main);

            return false;
        }

        if ('Вернуться к категориям' === $content) {
            // todo тут происходит прыжок

            return false;
        }

        return false;
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

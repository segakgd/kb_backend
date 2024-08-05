<?php

namespace App\Service\Constructor\Actions\Ecommerce;

use App\Helper\MessageHelper;
use App\Service\Common\Ecommerce\Product\Service\ProductService;
use App\Service\Common\PaginateService;
use App\Service\Constructor\Core\Actions\AbstractAction;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;
use Exception;

class ProductsByCategoryAction extends AbstractAction
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly PaginateService $paginateService,
    ) {}

    public static function getName(): string
    {
        return 'products.by.category.chain';
    }

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
    public function before(ResponsibleInterface $responsible): bool
    {
        $content = $responsible->getContent();

        if ('Добавить в корзину' === $content) {
            return true;
        }

        $categoryId = $responsible->getEvent()->getData()->getCategoryId();

        if ('Предыдущий' === $content) {
            $data = $responsible->getEvent()->getData();

            $products = $this->productService->getProductsByCategory($data->getPageNow(), $categoryId, 'prev');

            $this->paginateService->pug(
                responsible: $responsible,
                products: $products,
                cacheDataDto: $data
            );

            return false;
        }

        if ('Следующий' === $content) {
            $data = $responsible->getEvent()->getData();

            $products = $this->productService->getProductsByCategory($data->getPageNow(), $categoryId, 'next');

            $this->paginateService->pug(
                responsible: $responsible,
                products: $products,
                cacheDataDto: $data
            );

            return false;
        }

        //        if ('Вернуться в главное меню' === $content) {
        //            $responsible->setJumpToScenario(TargetEnum::Main);
        //
        //            return false;
        //        }

        if ('Вернуться к категориям' === $content) {
            // todo тут происходит прыжок

            return false;
        }

        return false;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        return $this->isValidContent(
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

    public function after(ResponsibleInterface $responsible): bool
    {
        return true;
    }
}

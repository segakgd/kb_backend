<?php

namespace App\Service\Constructor\Items;

use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\Admin\Ecommerce\ProductCategory\Service\ProductCategoryService;
use App\Service\Common\PaginateService;
use App\Service\Constructor\Core\Chains\AbstractChain;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;
use Exception;

class CategoriesChain extends AbstractChain
{
    public function __construct(
        private readonly ProductService         $productService,
        private readonly PaginateService        $paginateService,
        private readonly ProductCategoryService $categoryService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function complete(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $event = $responsible->getCacheDto()->getEvent();
        $content = $responsible->getCacheDto()->getContent();

        if ($responsible->getChain()->isRepeat()) {
            $categoryId = $responsible->getCacheDto()->getEvent()->getData()->getCategoryId();
            $availableCategory = $this->categoryService->getCategoryById($categoryId);
        } else {
            $availableCategory = $this->categoryService->getCategoryByName($content);
        }

        if (!$availableCategory) {
            throw new Exception("Неизвестная категория: $content");
        }

        $event->getData()->setCategoryId($availableCategory->getId());
        $event->getData()->setCategoryName($availableCategory->getName());

        $data = $responsible->getCacheDto()->getEvent()->getData();

        $products = $this->productService->getProductsByCategory(1,  $availableCategory->getId(),'first');

        $this->paginateService->pug($responsible, $products, $data);

        return $responsible;
    }

    public function condition(ResponsibleInterface $responsible): ConditionInterface
    {
        return $this->makeCondition(
            [
                [
                    [
                        'text' => 'Наушники'
                    ],
                    [
                        'text' => 'Ноутбуки'
                    ],
                ],
            ]
        );
    }

    public function perform(ResponsibleInterface $responsible): bool
    {
        return true;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        return $this->isValid(
            $responsible,
            [
                'Наушники',
                'Ноутбуки',
            ]
        );
    }
}

<?php

namespace App\Service\System\Constructor\Items;

use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\Admin\Ecommerce\ProductCategory\Service\ProductCategoryService;
use App\Service\System\Common\PaginateService;
use App\Service\System\Constructor\Core\Chains\AbstractChain;
use App\Service\System\Constructor\Core\Dto\Condition;
use App\Service\System\Constructor\Core\Dto\ConditionInterface;
use App\Service\System\Constructor\Core\Dto\ResponsibleInterface;
use Exception;

class ProductCategoryChain extends AbstractChain
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
    public function success(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $event = $responsible->getCacheDto()->getEvent();
        $content = $responsible->getCacheDto()->getContent();

        $availableCategory = $this->categoryService->getCategoryByName($content);

        if (!$availableCategory) {
            throw new Exception('Неизвестная категория');
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
        $replyMarkups = [
            [
                [
                    'text' => 'Наушники'
                ],
                [
                    'text' => 'Ноутбуки'
                ],
            ],
        ];

        $condition = new Condition();

        $condition->setKeyBoard($replyMarkups);

        return $condition;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        $content = $responsible->getCacheDto()->getContent();

        $validData = [
            'Наушники',
            'Ноутбуки',
        ];

        if (in_array($content, $validData)) {
            return true;
        }

        return false;
    }
}

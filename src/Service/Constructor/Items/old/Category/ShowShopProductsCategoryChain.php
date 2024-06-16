<?php

namespace App\Service\Constructor\Items\old\Category;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\ProductCategory\Service\ProductCategoryService;
use App\Service\Constructor\Core\Dto\Responsible;

readonly class ShowShopProductsCategoryChain // extends AbstractChain
{
    public function __construct(private ProductCategoryService $categoryService)
    {
    }

    public function success(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $availableCategory = $this->categoryService->getAvailableCategory(1);

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            'Отлично, 😜 выберите одну из категорий 🤘',
            null,
            KeyboardHelper::getProductCategoryNav($availableCategory)
        );

        $responsible->getResult()->setMessage($responsibleMessage);

        return true;
    }

    public function fall(Responsible $responsible, CacheDto $cacheDto): bool
    {
        return false;
    }

    public function validateCondition(string $content): bool
    {
        return true;
    }
}

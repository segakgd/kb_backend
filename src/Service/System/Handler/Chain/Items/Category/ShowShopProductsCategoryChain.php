<?php

namespace App\Service\System\Handler\Chain\Items\Category;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\ProductCategory\Service\ProductCategoryService;
use App\Service\System\Contract;
use App\Service\System\Handler\Chain\AbstractChain;

class ShowShopProductsCategoryChain extends AbstractChain
{
    public function __construct(private readonly ProductCategoryService $categoryService)
    {
    }

    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
        $availableCategory = $this->categoryService->getAvailableCategory(4842);

        $contractMessage = MessageHelper::createContractMessage(
            'Отлично, 😜 выберите одну из категорий 🤘',
            null,
            KeyboardHelper::getProductCategoryNav($availableCategory)
        );

        $contract->addMessage($contractMessage);

        return true;
    }

    public function fall(Contract $contract, CacheDto $cacheDto): bool
    {
        return false;
    }

    public function validateCondition(string $content): bool
    {
        return true;
    }
}

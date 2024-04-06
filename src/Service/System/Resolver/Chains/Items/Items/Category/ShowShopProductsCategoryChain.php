<?php

namespace App\Service\System\Resolver\Chains\Items\Items\Category;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\ProductCategory\Service\ProductCategoryService;
use App\Service\System\Handler\Chain\Items\Category\AbstractChain;
use App\Service\System\Resolver\Dto\Contract;

class ShowShopProductsCategoryChain extends AbstractChain
{
    public function __construct(private readonly ProductCategoryService $categoryService)
    {
    }

    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
        $availableCategory = $this->categoryService->getAvailableCategory(1);

        $contractMessage = MessageHelper::createContractMessage(
            'Отлично, 😜 выберите одну из категорий 🤘',
            null,
            KeyboardHelper::getProductCategoryNav($availableCategory)
        );

        $contract->getResult()->addMessage($contractMessage);

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

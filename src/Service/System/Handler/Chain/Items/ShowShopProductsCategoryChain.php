<?php

namespace App\Service\System\Handler\Chain\Items;

use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\ProductCategory\ProductCategoryService;
use App\Service\System\Contract;

class ShowShopProductsCategoryChain // 1
{
    public function __construct(private readonly ProductCategoryService $categoryService)
    {
    }

    public function handle(Contract $contract): bool
    {
        $availableCategory = $this->categoryService->getAvailableCategory();

        $contractMessage = MessageHelper::createContractMessage(
            'Отлично, 😜 выберите одну из категорий 🤘',
            null,
            KeyboardHelper::getProductCategoryNav($availableCategory)
        );

        $contract->addMessage($contractMessage);

        return true;
    }
}

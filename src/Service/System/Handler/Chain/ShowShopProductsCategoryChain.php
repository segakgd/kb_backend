<?php

namespace App\Service\System\Handler\Chain;

use App\Helper;
use App\Service\Admin\Ecommerce\ProductCategory\ProductCategoryService;
use App\Service\System\Handler\Contract;

class ShowShopProductsCategoryChain
{
    public function __construct(private readonly ProductCategoryService $categoryService)
    {
    }

    public function handle(Contract $contract): bool
    {
        $availableCategory = $this->categoryService->getAvailableCategory();

        $contractMessage = Helper::createContractMessage(
            'Отлично, 😜 выберите одну из категорий 🤘',
            null,
            Helper::getProductCategoryNav($availableCategory)
        );

        $contract->addMessage($contractMessage);

        return true;
    }
}

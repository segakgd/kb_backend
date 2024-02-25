<?php

namespace App\Service\System\Handler\Chain;

use App\Helper;
use App\Service\Admin\Ecommerce\ProductCategory\ProductCategoryService;
use App\Service\System\Handler\PreMessageDto;

class ShowShopProductsCategoryChain
{
    public function __construct(private readonly ProductCategoryService $categoryService)
    {
    }

    public function handle(PreMessageDto $preMessageDto): bool
    {
        $availableCategory = $this->categoryService->getAvailableCategory();

        $replyMarkups = Helper::getProductCategoryNav($availableCategory);

        $preMessageDto->setMessage('Отлично, 😜 выберите одну из категорий 🤘');
        $preMessageDto->setKeyBoard($replyMarkups);

        return true;
    }
}

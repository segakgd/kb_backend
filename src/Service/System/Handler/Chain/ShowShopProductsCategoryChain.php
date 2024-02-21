<?php

namespace App\Service\System\Handler\Chain;

use App\Dto\Core\Telegram\Request\Message\MessageDto;
use App\Service\Admin\Ecommerce\ProductCategory\ProductCategoryService;
use App\Service\System\Helper;

class ShowShopProductsCategoryChain
{
    public function __construct(private readonly ProductCategoryService $categoryService)
    {
    }

    public function handle(MessageDto $messageDto): bool
    {
        $availableCategory = $this->categoryService->getAvailableCategory();

        $replyMarkups = Helper::getProductCategoryNav($availableCategory);

        $messageDto->setText('Отлично, 😜 выберите одну из категорий 🤘');
        $messageDto->setReplyMarkup($replyMarkups);

        return true;
    }
}

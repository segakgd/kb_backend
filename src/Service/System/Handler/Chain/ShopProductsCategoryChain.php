<?php

namespace App\Service\System\Handler\Chain;

use App\Helper;
use App\Service\Admin\Ecommerce\ProductCategory\ProductCategoryService;
use App\Service\System\Handler\PreMessageDto;

class ShopProductsCategoryChain
{
    public function __construct(private readonly ProductCategoryService $categoryService)
    {
    }

    public function handle(PreMessageDto $preMessageDto, ?string $content = null): bool
    {
        if ($this->checkCondition($content)) {
            $preMessageDto->setMessage(
                'Вы выбрали категорию ' . $content . 'отличный выбор! В теперь давайте выберим товар:'
            );

            $replyMarkups = Helper::getProductNav();

            $preMessageDto->setKeyBoard($replyMarkups);

            return true;
        }

        if ($this->checkSystemCondition($content)) {
            $preMessageDto->setMessage('Давайте представим что вы вернулись в главное меню');

            return false;
        }

        $availableCategory = $this->categoryService->getAvailableCategory();

        $replyMarkups = Helper::getProductCategoryNav($availableCategory);

        $preMessageDto->setMessage(
            'Не понимаю вашего сообщения, выберите доступную категорию товара или вернитесь в глваное меню'
        );
        $preMessageDto->setKeyBoard($replyMarkups);

        return false;
    }

    private function checkCondition(string $content): bool
    {
        $availableCategory = $this->categoryService->getAvailableCategory();

        if (in_array($content, $availableCategory)) {
            return true;
        }

        return false;
    }

    private function checkSystemCondition(string $content): bool
    {
        $awaitsSystem = [
            'вернуться в главное меню',
        ];

        if (in_array($content, $awaitsSystem)) {
            return true;
        }

        return false;
    }
}

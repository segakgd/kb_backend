<?php

namespace App\Service\System\Handler\Chain;

use App\Dto\Core\Telegram\Request\Message\MessageDto;
use App\Service\Admin\Ecommerce\ProductCategory\ProductCategoryService;
use App\Service\System\Helper;

class ShopProductsCategoryChain
{
    public function __construct(private readonly ProductCategoryService $categoryService)
    {
    }

    public function handle(MessageDto $messageDto, ?string $content = null): bool
    {
        if ($this->checkCondition($content)) {
            $messageDto->setText(
                'Вы выбрали категорию ' . $content . 'отличный выбор! В теперь давайте выберим товар:'
            );

            $replyMarkups = Helper::getProductNav();

            $messageDto->setReplyMarkup($replyMarkups);

            return true;
        }

        if ($this->checkSystemCondition($content)) {
            $messageDto->setText('Давайте представим что вы вернулись в главное меню');

            return false;
        }

        $availableCategory = $this->categoryService->getAvailableCategory();

        $replyMarkups = Helper::getProductCategoryNav($availableCategory);

        $messageDto->setText(
            'Не понимаю вашего сообщения, выберите доступную категорию товара или вернитесь в глваное меню'
        );
        $messageDto->setReplyMarkup($replyMarkups);

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

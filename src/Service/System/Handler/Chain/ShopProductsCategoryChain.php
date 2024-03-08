<?php

namespace App\Service\System\Handler\Chain;

use App\Helper;
use App\Service\Admin\Ecommerce\ProductCategory\ProductCategoryService;
use App\Service\System\Handler\Contract;

class ShopProductsCategoryChain
{
    public function __construct(private readonly ProductCategoryService $categoryService)
    {
    }

    public function handle(Contract $contract, ?string $content = null): bool
    {
        $contractMessage = Helper::createContractMessage('');

        if ($this->checkCondition($content)) {
            $contractMessage->setMessage(
                'Вы выбрали категорию ' . $content . ' отличный выбор! В теперь давайте выберим товар:'
            );

            $replyMarkups = Helper::getProductNav();

            $contractMessage->setKeyBoard($replyMarkups);

            return true;
        }

        if ($this->checkSystemCondition($content)) {
            $contract->setGoto(Contract::GOTO_MAIN);

            return true;
        }

        $availableCategory = $this->categoryService->getAvailableCategory();

        $replyMarkups = Helper::getProductCategoryNav($availableCategory);

        $contractMessage->setMessage(
            'Не понимаю вашего сообщения, выберите доступную категорию товара или вернитесь в глваное меню'
        );
        $contractMessage->setKeyBoard($replyMarkups);

        $contract->addMessage($contractMessage);

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

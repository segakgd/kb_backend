<?php

namespace App\Service\System\Handler\Items;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\ProductCategory\ProductCategoryService;
use App\Service\System\Common\PaginateService;
use App\Service\System\Contract;
use Exception;

class ShopProductsCategoryChain
{
    public function __construct(
        private readonly ProductCategoryService $categoryService,
        private readonly PaginateService $paginateService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(Contract $contract, CacheDto $cacheDto): bool
    {
        $contractMessage = MessageHelper::createContractMessage('');
        $content = $cacheDto->getContent();

        if ($this->checkCondition($content)) {
            $event = $cacheDto->getEvent();

            $event->getData()->setCategoryName($content);

            $this->paginateService->first($contract, $event->getData());

            $replyMarkups = KeyboardHelper::getProductNav();

            $contractMessage->setKeyBoard($replyMarkups);

            return true;
        }

        if ($this->checkSystemCondition($content)) {
            $contract->setGoto(Contract::GOTO_MAIN);

            return true;
        }

        $availableCategory = $this->categoryService->getAvailableCategory();

        $replyMarkups = KeyboardHelper::getProductCategoryNav($availableCategory);

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

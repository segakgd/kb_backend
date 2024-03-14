<?php

namespace App\Service\System\Handler\Chain\Items;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\ProductCategory\ProductCategoryService;
use App\Service\System\Common\PaginateService;
use App\Service\System\Contract;
use Exception;

class ShopProductsCategoryChain extends AbstractChain
{
    public function __construct(
        private readonly ProductCategoryService $categoryService,
        private readonly PaginateService $paginateService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
        $contractMessage = MessageHelper::createContractMessage('');
        $content = $cacheDto->getContent();

        if ($this->checkSystemCondition($content)) {
            $contract->setGoto(Contract::GOTO_MAIN);

            return true;
        }

        $event = $cacheDto->getEvent();

        $event->getData()->setCategoryName($content);

        $this->paginateService->first($contract, $event->getData());

        $replyMarkups = KeyboardHelper::getProductNav();

        $contractMessage->setKeyBoard($replyMarkups);

        return true;
    }

    public function fall(Contract $contract, CacheDto $cacheDto): bool
    {
        $contractMessage = MessageHelper::createContractMessage('');

        $availableCategory = $this->categoryService->getAvailableCategory();

        $replyMarkups = KeyboardHelper::getProductCategoryNav($availableCategory);

        $contractMessage->setMessage(
            'Не понимаю вашего сообщения, выберите доступную категорию товара или вернитесь в глваное меню'
        );
        $contractMessage->setKeyBoard($replyMarkups);

        $contract->addMessage($contractMessage);

        return false;
    }

    public function validateCondition(string $content): bool
    {
        $availableCategory = $this->categoryService->getAvailableCategory();
        $availableCategory[] = 'вернуться в главное меню';

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

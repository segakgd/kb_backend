<?php

namespace App\Service\System\Handler\Chain\Items;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\ProductCategory\ProductCategoryService;
use App\Service\System\Common\PaginateService;
use App\Service\System\Contract;
use App\Service\System\Handler\Chain\AbstractChain;
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

        $availableCategory = $this->categoryService->getCategoryByName($content);

        if (!$availableCategory) {
            throw new Exception('Неизвестная категория');
        }

        $event = $cacheDto->getEvent();

        $event->getData()->setCategoryId($availableCategory->getId());
        $event->getData()->setCategoryName($availableCategory->getName());

        $this->paginateService->first($contract, $event->getData());

        $replyMarkups = KeyboardHelper::getProductNav();

        $contractMessage->setKeyBoard($replyMarkups);

        return true;
    }

    public function fall(Contract $contract, CacheDto $cacheDto): bool
    {
        $contractMessage = MessageHelper::createContractMessage('');

        $availableCategory = $this->categoryService->getAvailableCategory(4842);

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
        $availableCategory = $this->categoryService->getAvailableCategory(4842);

        if (in_array($content, $availableCategory)) {
            return true;
        }

        return false;
    }
}

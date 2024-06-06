<?php

namespace App\Service\System\Core\Chains\Items\Items\Category;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\ProductCategory\Service\ProductCategoryService;
use App\Service\System\Common\PaginateService;
use App\Service\System\Core\Dto\Responsible;
use Exception;

readonly class ShopProductsCategoryChain // extends AbstractChain
{
    public function __construct(
        private ProductCategoryService $categoryService,
        private PaginateService        $paginateService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function success(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $responsibleMessage = MessageHelper::createResponsibleMessage('');
        $content = $cacheDto->getContent();

        $availableCategory = $this->categoryService->getCategoryByName($content);

        if (!$availableCategory) {
            throw new Exception('Неизвестная категория');
        }

        $event = $cacheDto->getEvent();

        $event->getData()->setCategoryId($availableCategory->getId());
        $event->getData()->setCategoryName($availableCategory->getName());

        $this->paginateService->first($responsible, $event->getData());

        $replyMarkups = KeyboardHelper::getProductNav();

        $responsibleMessage->setKeyBoard($replyMarkups);

        return true;
    }

    public function fall(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $responsibleMessage = MessageHelper::createResponsibleMessage('');

        $availableCategory = $this->categoryService->getAvailableCategory(1);

        $replyMarkups = KeyboardHelper::getProductCategoryNav($availableCategory);

        $responsibleMessage->setMessage(
            'Не понимаю вашего сообщения, выберите доступную категорию товара или вернитесь в глваное меню'
        );
        $responsibleMessage->setKeyBoard($replyMarkups);

        $responsible->getResult()->addMessage($responsibleMessage);

        return false;
    }

    public function validateCondition(string $content): bool
    {
        $availableCategory = $this->categoryService->getAvailableCategory(1);

        if (in_array($content, $availableCategory)) {
            return true;
        }

        return false;
    }
}

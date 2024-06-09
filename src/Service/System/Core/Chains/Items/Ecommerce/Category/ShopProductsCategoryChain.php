<?php

namespace App\Service\System\Core\Chains\Items\Ecommerce\Category;

use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\ProductCategory\Service\ProductCategoryService;
use App\Service\System\Common\PaginateService;
use App\Service\System\Core\Chains\Items\AbstractChain;
use App\Service\System\Core\Dto\Condition;
use App\Service\System\Core\Dto\ConditionInterface;
use App\Service\System\Core\Dto\ResponsibleInterface;
use Exception;

class ShopProductsCategoryChain extends AbstractChain
{
    public function __construct(
        private readonly ProductCategoryService $categoryService,
        private readonly PaginateService        $paginateService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function success(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $responsibleMessage = MessageHelper::createResponsibleMessage('');
        $cacheDto = $responsible->getCacheDto();
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

        return $responsible;
    }

    public function condition(): ConditionInterface
    {
        $replyMarkups = [
            [
                [
                    'text' => 'Поставить состояние для ' . static::class
                ],
            ],
        ];

        $condition = new Condition();

        $condition->setKeyBoard($replyMarkups);

        return $condition;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        $availableCategory = $this->categoryService->getAvailableCategory(1);

        if (in_array($responsible->getCacheDto()->getContent(), $availableCategory)) {
            return true;
        }

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
}

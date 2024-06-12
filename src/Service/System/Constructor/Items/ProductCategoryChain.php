<?php

namespace App\Service\System\Constructor\Items;

use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\ProductCategory\Service\ProductCategoryService;
use App\Service\System\Common\PaginateService;
use App\Service\System\Constructor\Core\Chains\AbstractChain;
use App\Service\System\Constructor\Core\Dto\Condition;
use App\Service\System\Constructor\Core\Dto\ConditionInterface;
use App\Service\System\Constructor\Core\Dto\ResponsibleInterface;
use Exception;

class ProductCategoryChain extends AbstractChain
{
    public function __construct(
        private readonly PaginateService        $paginateService,
        private readonly ProductCategoryService $categoryService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function success(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $message = "Выберите одну из достуаных категорий товаров: ";

        $event = $responsible->getCacheDto()->getEvent();
        $content = $responsible->getCacheDto()->getContent();

        $availableCategory = $this->categoryService->getCategoryByName($content);

        if (!$availableCategory) {
            throw new Exception('Неизвестная категория');
        }

        $event->getData()->setCategoryId($availableCategory->getId());
        $event->getData()->setCategoryName($availableCategory->getName());

        $this->paginateService->first($responsible, $event->getData());

        $replyMarkups = KeyboardHelper::getProductNav();

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
            keyBoard: $replyMarkups
        );

        $responsible->getResult()->addMessage($responsibleMessage);

        return $responsible;
    }

    public function condition(ResponsibleInterface $responsible): ConditionInterface
    {
        $replyMarkups = [
            [
                [
                    'text' => 'Наушники'
                ],
                [
                    'text' => 'Ноутбуки'
                ],
            ],
        ];

        $condition = new Condition();

        $condition->setKeyBoard($replyMarkups);

        return $condition;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        $content = $responsible->getCacheDto()->getContent();

        $validData = [
            'Наушники',
            'Ноутбуки',
        ];

        if (in_array($content, $validData)) {
            return true;
        }

        return false;
    }
}

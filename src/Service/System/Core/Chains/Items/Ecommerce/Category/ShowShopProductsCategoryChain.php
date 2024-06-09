<?php

namespace App\Service\System\Core\Chains\Items\Ecommerce\Category;

use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\ProductCategory\Service\ProductCategoryService;
use App\Service\System\Core\Chains\Items\AbstractChain;
use App\Service\System\Core\Dto\Condition;
use App\Service\System\Core\Dto\ConditionInterface;
use App\Service\System\Core\Dto\ResponsibleInterface;

class ShowShopProductsCategoryChain extends AbstractChain
{
    public function __construct(private readonly ProductCategoryService $categoryService)
    {
    }

    public function success(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $availableCategory = $this->categoryService->getAvailableCategory(1);

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            'Отлично, 😜 выберите одну из категорий 🤘',
            null,
            KeyboardHelper::getProductCategoryNav($availableCategory)
        );

        $responsible->getResult()->addMessage($responsibleMessage);

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
        return true;
    }
}

<?php

namespace App\Service\System\Core\Chains\Items\Ecommerce\Promo;

use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\System\Common\PaginateService;
use App\Service\System\Core\Chains\Items\AbstractChain;
use App\Service\System\Core\Dto\Condition;
use App\Service\System\Core\Dto\ConditionInterface;
use App\Service\System\Core\Dto\ResponsibleInterface;
use Exception;

class ShopProductsPromoChain extends AbstractChain
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly PaginateService $paginateService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function success(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $products = $this->productService->getPromoProducts(1, 'first');

        $this->paginateService->pug(
            responsible: $responsible,
            products: $products,
            cacheDataDto: $responsible->getCacheDto()->getEvent()->getData()
        );

        return $responsible;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        if ($responsible->getCacheDto()->getContent() === 'Акционные товары') {
            return true;
        }

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            'Не понимаю о чем вы...',
        );

        $responsible->getResult()->addMessage($responsibleMessage);

        return false;
    }

    public function condition(): ConditionInterface
    {
        $replyMarkups = [
            [
                [
                    'text' => 'Да 2'
                ],
                [
                    'text' => 'Нет 2'
                ],
            ],
        ];

        $condition = new Condition();

        $condition->setKeyBoard($replyMarkups);

        return $condition;
    }
}

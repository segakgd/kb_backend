<?php

namespace App\Service\System\Core\Chains\Items\Ecommerce\Popular;

use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\System\Common\PaginateService;
use App\Service\System\Core\Chains\Items\AbstractChain;
use App\Service\System\Core\Dto\Condition;
use App\Service\System\Core\Dto\ConditionInterface;
use App\Service\System\Core\Dto\ResponsibleInterface;
use Exception;

class ShopProductsPopularChain extends AbstractChain
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
        $products = $this->productService->getPopularProducts(1, 'first');

        $this->paginateService->pug($responsible, $products, $responsible->getCacheDto()->getEvent()->getData());

        return $responsible;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        if ($responsible->getCacheDto()->getEvent() === 'Популярные товары') {
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
                    'text' => 'ПОсмтавить состояние для ' . static::class
                ],
            ],
        ];

        $condition = new Condition();

        $condition->setKeyBoard($replyMarkups);

        return $condition;
    }
}

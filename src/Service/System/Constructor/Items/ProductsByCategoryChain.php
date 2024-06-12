<?php

namespace App\Service\System\Constructor\Items;

use App\Enum\JumpEnum;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\System\Common\PaginateService;
use App\Service\System\Constructor\Core\Chains\AbstractChain;
use App\Service\System\Constructor\Core\Dto\Condition;
use App\Service\System\Constructor\Core\Dto\ConditionInterface;
use App\Service\System\Constructor\Core\Dto\ResponsibleInterface;
use Exception;

class ProductsByCategoryChain extends AbstractChain
{
    public function __construct(
        private readonly ProductService  $productService,
        private readonly PaginateService $paginateService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function success(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $content = $responsible->getCacheDto()->getContent();

        $message = "Тут должен быть товар";

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
            // keyBoard: $responsible->getNextCondition()->getKeyBoard()
        );

        $responsible->getResult()->addMessage($responsibleMessage);

        $products = match ($content) {
            'first' => $this->productService->getPopularProducts(1, 'first'),
            'предыдущий' => $this->productService->getPopularProducts($responsible->getCacheDto()->getEvent()->getData()->getPageNow(), 'prev'),
            'следующий' => $this->productService->getPopularProducts($responsible->getCacheDto()->getEvent()->getData()->getPageNow(), 'next'),
        };


        $this->paginateService->pug($responsible, $products, $responsible->getCacheDto()->getEvent()->getData());

        if ($content === 'Предыдущий') {


            $responsible->setJump(JumpEnum::ProductsByCategoryChain);
        }

        if ($content === 'Следующий') {


            $responsible->setJump(JumpEnum::ProductsByCategoryChain);
        }

        return $responsible;
    }

    public function condition(ResponsibleInterface $responsible): ConditionInterface
    {
        $replyMarkups = [
            [
                [
                    'text' => 'Предыдущий'
                ],
                [
                    'text' => 'Следующий'
                ],
                [
                    'text' => 'Добавить в корзину'
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
            'Предыдущий',
            'Следующий',
            'Добавить в корзину',
        ];

        if (in_array($content, $validData)) {
            return true;
        }

        return false;
    }
}

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

        $message = "Допустим что, что-то ещё случилось. Ну, товар ты выбрал или ешё чего. И да, кликнул ты вот это: $content";

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
        );

        $responsible->getResult()->addMessage($responsibleMessage);

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

    /**
     * @throws Exception
     */
    public function validate(ResponsibleInterface $responsible): bool
    {
        $content = $responsible->getCacheDto()->getContent();

        $categoryId = $responsible->getCacheDto()->getEvent()->getData()->getCategoryId();

        if ($content === 'Предыдущий') {
            $data = $responsible->getCacheDto()->getEvent()->getData();

            $products = $this->productService->getProductsByCategory($data->getPageNow(), $categoryId, 'prev');

            $this->paginateService->pug($responsible, $products, $data);

            return false;
        }

        if ($content === 'Следующий') {
            $data = $responsible->getCacheDto()->getEvent()->getData();

            $products = $this->productService->getProductsByCategory($data->getPageNow(), $categoryId, 'next');

            $this->paginateService->pug($responsible, $products, $data);

            return false;
        }

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

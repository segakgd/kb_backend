<?php

namespace App\Service\System\Core\Chains\Items\Ecommerce\Promo;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\System\Common\PaginateService;
use App\Service\System\Core\Chains\Items\AbstractChain;
use App\Service\System\Core\Dto\Condition;
use App\Service\System\Core\Dto\ConditionInterface;
use App\Service\System\Core\Dto\Responsible;
use App\Service\System\Core\Dto\ResponsibleInterface;
use Exception;

class ShopProductPromoChain extends AbstractChain
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

//        if ($cacheDto->getEvent()->getCurrentChain()->isRepeat()) {
//            $cacheDto->getEvent()->getCurrentChain()->setRepeat(false);
//
//            $content = 'first'; // todo мб возвращать на тот товар с которого ушли?
//        }

        $event = $responsible->getCacheDto()->getEvent();

        if ($content === 'добавить в корзину') {
            $this->addToCart($responsible, $responsible->getCacheDto());

            return $responsible;
        }

        $products = match ($content) {
            'first' => $this->productService->getPromoProducts(1, 'first'),
            'предыдущий' => $this->productService->getPromoProducts($event->getData()->getPageNow(), 'prev'),
            'следующий' => $this->productService->getPromoProducts($event->getData()->getPageNow(), 'next'),
            default => false
        };

        if ($products) {
            $this->paginateService->pug($responsible, $products, $responsible->getCacheDto()->getEvent()->getData());

            return $responsible; // false
        }

        return $responsible; // false
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        $availableProductNavItems = KeyboardHelper::getAvailableProductNavItems();

        if (in_array($responsible->getCacheDto()->getContent(), $availableProductNavItems)) {
            return true;
        }

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

    public function fall(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $replyMarkups = KeyboardHelper::getProductNav();

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            'Не понимаю о чем вы... мб вам выбрать доступные варианты из меню? К примеру, вы можете посмотреть более подробную информациюю о товаре.',
            null,
            $replyMarkups,
        );

        $responsible->getResult()->addMessage($responsibleMessage);

        return false;
    }

    public function validateCondition(string $content): bool
    {
        $availableProductNavItems = KeyboardHelper::getAvailableProductNavItems();

        if (in_array($content, $availableProductNavItems)) {
            return true;
        }

        return false;
    }

    private function addToCart(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $productId = $cacheDto->getEvent()->getData()->getProductId();
        $responsibleMessage = MessageHelper::createResponsibleMessage('');

        $product = $this->productService->find($productId);
        $variants = $product->getVariants();

        $variantsNav = KeyboardHelper::getVariantsNav($variants);

        $responsibleMessage->setKeyBoard($variantsNav);
        $responsibleMessage->setMessage('Добавить в корзину вариант:');

        $responsible->getResult()->addMessage($responsibleMessage);

        return true;
    }
}

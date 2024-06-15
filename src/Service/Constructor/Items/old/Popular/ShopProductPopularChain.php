<?php

namespace App\Service\Constructor\Items\old\Popular;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\Common\PaginateService;
use App\Service\Constructor\Core\Dto\Responsible;
use Exception;

readonly class ShopProductPopularChain // extends AbstractChain
{
    public function __construct(
        private ProductService  $productService,
        private PaginateService $paginateService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function success(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();

//        if ($cacheDto->getEvent()->getCurrentChain()->isRepeat()) {
//            $cacheDto->getEvent()->getCurrentChain()->setRepeat(false);
//
//            $content = 'first'; // todo мб возвращать на тот товар с которого ушли?
//        }

        $event = $cacheDto->getEvent();

        if ($content === 'добавить в корзину') {
            return $this->addToCart($responsible, $cacheDto);
        }

        $products = match ($content) {
            'first' => $this->productService->getPopularProducts(1, 'first'),
            'предыдущий' => $this->productService->getPopularProducts($event->getData()->getPageNow(), 'prev'),
            'следующий' => $this->productService->getPopularProducts($event->getData()->getPageNow(), 'next'),
            default => false
        };

        if ($products) {
            $this->paginateService->pug($responsible, $products, $cacheDto->getEvent()->getData());

            return false;
        }

        return false;
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

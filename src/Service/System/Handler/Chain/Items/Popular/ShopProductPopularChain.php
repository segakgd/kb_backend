<?php

namespace App\Service\System\Handler\Chain\Items\Popular;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\ProductService;
use App\Service\System\Common\PaginateService;
use App\Service\System\Contract;
use App\Service\System\Handler\Chain\AbstractChain;
use Exception;

class ShopProductPopularChain extends AbstractChain
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly PaginateService $paginateService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();

        if ($cacheDto->getEvent()->getCurrentChain()->isRepeat()) {
            $cacheDto->getEvent()->getCurrentChain()->setRepeat(false);

            $content = 'first'; // todo мб возвращать на тот товар с которого ушли?
        }

        $event = $cacheDto->getEvent();

        if ($content === 'добавить в корзину') {
            return $this->addToCart($contract, $cacheDto);
        }

        $products = match ($content) {
            'first' => $this->productService->getPopularProducts(1, 'first'),
            'предыдущий' => $this->productService->getPopularProducts($event->getData()->getPageNow(), 'prev'),
            'следующий' => $this->productService->getPopularProducts($event->getData()->getPageNow(), 'next'),
            default => false
        };

        if ($products) {
            $this->paginateService->pug($contract, $products, $cacheDto->getEvent()->getData());

            return false;
        }

        return false;
    }

    public function fall(Contract $contract, CacheDto $cacheDto): bool
    {
        $replyMarkups = KeyboardHelper::getProductNav();

        $contractMessage = MessageHelper::createContractMessage(
            'Не понимаю о чем вы... мб вам выбрать доступные варианты из меню? К примеру, вы можете посмотреть более подробную информациюю о товаре.',
            null,
            $replyMarkups,
        );

        $contract->addMessage($contractMessage);

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

    private function addToCart(Contract $contract, CacheDto $cacheDto): bool
    {
        $productId = $cacheDto->getEvent()->getData()->getProductId();
        $contractMessage = MessageHelper::createContractMessage('');

        $product = $this->productService->find($productId);
        $variants = $product->getVariants();

        $variantsNav = KeyboardHelper::getVariantsNav($variants);

        $contractMessage->setKeyBoard($variantsNav);
        $contractMessage->setMessage('Добавить в корзину вариант:');

        $contract->addMessage($contractMessage);

        return true;
    }
}

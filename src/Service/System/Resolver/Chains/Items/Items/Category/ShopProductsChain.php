<?php

namespace App\Service\System\Resolver\Chains\Items\Items\Category;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\System\Common\PaginateService;
// use App\Service\System\Handler\Chain\AbstractChain;
use App\Service\System\Resolver\Dto\Contract;
use Exception;

class ShopProductsChain // extends AbstractChain
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

//        if ($cacheDto->getEvent()->getCurrentChain()->isRepeat()) {
//            $cacheDto->getEvent()->getCurrentChain()->setRepeat(false);
//
//            $content = 'first'; // todo мб возвращать на тот товар с которого ушли?
//        }

        $event = $cacheDto->getEvent();

        return match ($content) {
            'first' => $this->paginateService->first($contract, $event->getData()),
            'предыдущий' => $this->paginateService->prev($contract, $event->getData()),
            'следующий' => $this->paginateService->next($contract, $event->getData()),
            'добавить в корзину' => $this->addToCart($contract, $cacheDto),
            default => false
        };
    }

    public function fall(Contract $contract, CacheDto $cacheDto): bool
    {
        $replyMarkups = KeyboardHelper::getProductNav();

        $contractMessage = MessageHelper::createContractMessage(
            'Не понимаю о чем вы... мб вам выбрать доступные варианты из меню? К примеру, вы можете посмотреть более подробную информациюю о товаре.',
            null,
            $replyMarkups,
        );

        $contract->getResult()->addMessage($contractMessage);

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

        $contract->getResult()->addMessage($contractMessage);

        return true;
    }
}

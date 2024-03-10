<?php

namespace App\Service\System\Handler\Chain\Items;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\ProductService;
use App\Service\System\Common\PaginateService;
use App\Service\System\Contract;
use Exception;

class ShopProductsChain // 3
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly PaginateService $paginateService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(Contract $contract, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();

        if ($this->checkSystemCondition($content)) {
            $event = $cacheDto->getEvent();

            return match ($content) {
                'предыдущий' => $this->paginateService->prev($contract, $event->getData()),
                'следующий' => $this->paginateService->next($contract, $event->getData()),
                'добавить в корзину' => $this->addToCart($contract, $cacheDto),
                'вернуться в главное меню' => $this->gotoMain($contract),
                default => false
            };
        }

        $replyMarkups = KeyboardHelper::getProductNav();

        $contractMessage = MessageHelper::createContractMessage(
            'Не понимаю о чем вы... мб вам выбрать доступные варианты из меню? К примеру, вы можете посмотреть более подробную информациюю о товаре.',
            null,
            $replyMarkups,
        );

        $contract->addMessage($contractMessage);

        return false;
    }

    private function checkSystemCondition(string $content): bool
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

    private function gotoMain(Contract $contract): bool
    {
        $contract->setGoto(Contract::GOTO_MAIN);

        return true;
    }
}

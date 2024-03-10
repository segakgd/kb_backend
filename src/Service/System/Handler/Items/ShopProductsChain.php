<?php

namespace App\Service\System\Handler\Items;

use App\Dto\SessionCache\Cache\CacheDataDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\ProductService;
use App\Service\System\Common\PaginateService;
use App\Service\System\Contract;
use Exception;

class ShopProductsChain
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
                'добавить в корзину' => $this->addToCart($contract, $event->getData()),
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

    private function addToCart(Contract $contract, CacheDataDto $paginate): bool
    {
        $productId = $paginate->getProductId();
        $contractMessage = MessageHelper::createContractMessage('');

        $product = $this->productService->find($productId);

        $variants = $product->getVariants();
        $variantCount = $variants->count();

        if ($variantCount > 1) {
            $variantsNav = KeyboardHelper::getVariantsNav($variants);

            $contractMessage->setKeyBoard($variantsNav);
            $contractMessage->setMessage('addToCart');

//            $contract->setGoto(Contract::GOTO_NEXT);

            return false;
        }

//        $contract->setGoto(Contract::GOTO_NEXT);
        $contract->addMessage($contractMessage);

        return false;
    }

    private function gotoMain(Contract $contract): bool
    {
        $contract->setGoto(Contract::GOTO_MAIN);

        return true;
    }
}

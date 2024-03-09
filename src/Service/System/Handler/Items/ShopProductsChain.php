<?php

namespace App\Service\System\Handler\Items;

use App\Dto\SessionCache\Cache\CacheDataDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\Ecommerce\Product;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\ProductService;
use App\Service\System\Contract;
use Exception;

class ShopProductsChain
{
    public function __construct(
        private readonly ProductService $productService,
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
                'предыдущий' => $this->prev($contract, $event->getData()),
                'добавить в корзину' => $this->addToCart($contract, $event->getData()),
                'следующий' => $this->next($contract, $event->getData()),
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

    /**
     * @throws Exception
     */
    private function prev(Contract $contract, CacheDataDto $paginate): bool
    {
        $products = $this->productService->getProductsByCategory($paginate->getPageNow(), 'магнитолы', 'product.prev');

        $contractMessage = MessageHelper::createContractMessage('');

        /** @var Product $product */
        $product = $products['items'][0];

        $paginate->setPageNow($products['paginate']['now']);
        $paginate->setProductId($product->getId());

        $message = MessageHelper::renderProductMessage($product);

        $photo = 'https://sopranoclub.ru/images/190-epichnyh-anime-artov/file48822.jpg';

        $contractMessage->setMessage($message);
        $contractMessage->setPhoto($photo);

        if ($products['paginate']['prev'] === null) {
            $replyMarkups = KeyboardHelper::getProductNav(['next' => true]);
        } else {
            $replyMarkups = KeyboardHelper::getProductNav();
        }

        $contractMessage->setKeyBoard($replyMarkups);

        $contract->addMessage($contractMessage);

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

    /**
     * @throws Exception
     */
    private function next(Contract $contract, CacheDataDto $paginate): bool
    {
        $products = $this->productService->getProductsByCategory($paginate->getPageNow(), 'магнитолы', 'product.next');
        $contractMessage = MessageHelper::createContractMessage('');

        /** @var Product $product */
        $product = $products['items'][0];

        $paginate->setPageNow($products['paginate']['now']);
        $paginate->setProductId($product->getId());

        $message = MessageHelper::renderProductMessage($product);

        $photo = 'https://sopranoclub.ru/images/190-epichnyh-anime-artov/file48822.jpg';

        $contractMessage->setMessage($message);
        $contractMessage->setPhoto($photo);

        if ($products['paginate']['next'] === null) {
            $replyMarkups = KeyboardHelper::getProductNav(['prev' => true]);
        } else {
            $replyMarkups = KeyboardHelper::getProductNav();
        }

        $contractMessage->setKeyBoard($replyMarkups);

        $contract->addMessage($contractMessage);

        return false;
    }

    private function gotoMain(Contract $contract): bool
    {
        $contract->setGoto(Contract::GOTO_MAIN);

        return true;
    }
}

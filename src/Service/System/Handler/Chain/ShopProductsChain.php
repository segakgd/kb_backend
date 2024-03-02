<?php

namespace App\Service\System\Handler\Chain;

use App\Entity\Ecommerce\Product;
use App\Helper;
use App\Service\Admin\Ecommerce\Product\ProductService;
use App\Service\System\Handler\Contract;
use App\Service\System\Handler\Dto\Cache\CacheDto;
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
            $paginate = $event['paginate']; // todo paginate переименовать

            $result = match ($content) {
                'предыдущий' => $this->prev($contract, $paginate),
                'добавить в корзину' => $this->addToCart($contract, $paginate),
                'следующий' => $this->next($contract, $paginate),
                'вернуться в главное меню' => $this->gotoMain($contract),
                default => false
            };

            $event['paginate'] = $paginate;

            $cacheDto->setEvent($event);

            return $result;
        }

        $replyMarkups = Helper::getProductNav();

        $contractMessage = Helper::createContractMessage(
            'Не понимаю о чем вы... мб вам выбрать доступные варианты из меню? К примеру, вы можете посмотреть более подробную информациюю о товаре.',
            null,
            $replyMarkups,
        );

        $contract->addMessage($contractMessage);

        return false;
    }

    private function checkSystemCondition(string $content): bool
    {
        $availableProductNavItems = Helper::getAvailableProductNavItems();

        if (in_array($content, $availableProductNavItems)) {
            return true;
        }

        return false;
    }

    /**
     * @throws Exception
     */
    private function prev(Contract $contract, array &$paginate): bool
    {
        $products = $this->productService->getProductsByCategory($paginate['now'], 'магнитолы', 'product.prev');

        $contractMessage = Helper::createContractMessage('');

        /** @var Product $product */
        $product = $products['items'][0];

        $paginate['now'] = $products['paginate']['now'];
        $paginate['productId'] = $product->getId();

        $message = Helper::renderProductMessage($product);

        $photo = 'https://sopranoclub.ru/images/190-epichnyh-anime-artov/file48822.jpg';

        $contractMessage->setMessage($message);
        $contractMessage->setPhoto($photo);

        if ($products['paginate']['prev'] === null) {
            $replyMarkups = Helper::getProductNav(['next' => true]);
        } else {
            $replyMarkups = Helper::getProductNav();
        }

        $contractMessage->setKeyBoard($replyMarkups);

        $contract->addMessage($contractMessage);

        return false;
    }

    private function addToCart(Contract $contract, array $paginate): bool
    {
        $productId = $paginate['productId'];
        $contractMessage = Helper::createContractMessage('');

        $product = $this->productService->find($productId);

        $variants = $product->getVariants();
        $variantCount = $variants->count();

        if ($variantCount > 1) {
            $variantsNav = Helper::getVariantsNav($variants);

            $contractMessage->setKeyBoard($variantsNav);
            $contractMessage->setMessage('addToCart');

            $contract->setGoto(Contract::GOTO_NEXT);

            return false;
        }

        $contract->setGoto(Contract::GOTO_NEXT);
        $contract->addMessage($contractMessage);

        return false;
    }

    /**
     * @throws Exception
     */
    private function next(Contract $contract, array &$paginate): bool
    {
        $products = $this->productService->getProductsByCategory($paginate['now'], 'магнитолы', 'product.next');
        $contractMessage = Helper::createContractMessage('');

        /** @var Product $product */
        $product = $products['items'][0];

        $paginate['now'] = $products['paginate']['now'];
        $paginate['productId'] = $product->getId();

        $message = Helper::renderProductMessage($product);

        $photo = 'https://sopranoclub.ru/images/190-epichnyh-anime-artov/file48822.jpg';

        $contractMessage->setMessage($message);
        $contractMessage->setPhoto($photo);

        if ($products['paginate']['next'] === null) {
            $replyMarkups = Helper::getProductNav(['prev' => true]);
        } else {
            $replyMarkups = Helper::getProductNav();
        }

        $contractMessage->setKeyBoard($replyMarkups);

        $contract->addMessage($contractMessage);

        return false;
    }

    private function gotoMain(Contract $contract): bool
    {
        $contractMessage = Helper::createContractMessage('');

        $contractMessage->setMessage('gotoMain');

        $contract->addMessage($contractMessage);

        return false;
    }
}

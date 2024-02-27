<?php

namespace App\Service\System\Handler\Chain;

use App\Entity\Ecommerce\Product;
use App\Helper;
use App\Service\Admin\Ecommerce\Product\ProductService;
use App\Service\System\Handler\Dto\CacheDto;
use App\Service\System\Handler\PreMessageDto;
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
    public function handle(PreMessageDto $preMessageDto, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();

        if ($this->checkSystemCondition($content)) {
            $event = $cacheDto->getEvent();
            $paginate = $event['paginate'];

//            $result = match ($content) {
//                'предыдущий' => 'product.prev',
//                'следующий' => 'product.next',
//                'подробнее о товаре' => $this->aboutProduct($preMessageDto),
//                'вернуться в главное меню' => $this->gotoMain($preMessageDto),
//                default => false
//            };
//
            $result = match ($content) {
                'предыдущий' => $this->prev($preMessageDto, $paginate),
                'подробнее о товаре' => $this->aboutProduct($preMessageDto),
                'следующий' => $this->next($preMessageDto, $paginate),
                'вернуться в главное меню' => $this->gotoMain($preMessageDto),
                default => false
            };
//            dd($paginate);

//            dd($paginate['now'], $products['paginate']['total']);
//            dd(Helper::buildPaginate($paginate['now'], $products['paginate']['total']));

            $event['paginate'] = $paginate;

            $cacheDto->setEvent($event);

            return $result;
        }

        $replyMarkups = Helper::getProductNav();

        $preMessageDto->setMessage(
            'Не понимаю о чем вы... мб вам выбрать доступные варианты из меню? К примеру, вы можете посмотреть более подробную информациюю о товаре.'
        );
        $preMessageDto->setKeyBoard($replyMarkups);

        return false;
    }

    /**
     * @throws Exception
     */
    private function prev(PreMessageDto $preMessageDto, array &$paginate): bool
    {
        $products = $this->productService->getProductsByCategory($paginate['now'], 'магнитолы', 'product.prev');

        $paginate['now'] = $products['paginate']['now'];

        $product = $products['items'][0];

        $message = Helper::renderProductMessage($product);

        $photo = 'https://sopranoclub.ru/images/190-epichnyh-anime-artov/file48822.jpg';

        $preMessageDto->setMessage($message);
        $preMessageDto->setPhoto($photo);

//        dd($products);
        if ($products['paginate']['prev'] === null){
            $replyMarkups = Helper::getProductNav(['next' => true]);
        } else {
            $replyMarkups = Helper::getProductNav();
        }

        $preMessageDto->setKeyBoard($replyMarkups);

        return false;
    }

    /**
     * @throws Exception
     */
    private function next(PreMessageDto $preMessageDto, array &$paginate): bool
    {
        $products = $this->productService->getProductsByCategory($paginate['now'], 'магнитолы', 'product.next');

        $paginate['now'] = $products['paginate']['now'];

        $product = $products['items'][0];

        $message = Helper::renderProductMessage($product);

        $photo = 'https://sopranoclub.ru/images/190-epichnyh-anime-artov/file48822.jpg';

        $preMessageDto->setMessage($message);
        $preMessageDto->setPhoto($photo);

        if ($products['paginate']['next'] === null){
            $replyMarkups = Helper::getProductNav(['prev' => true]);
        } else {
            $replyMarkups = Helper::getProductNav();
        }

        $preMessageDto->setKeyBoard($replyMarkups);

        return false;
    }

    private function aboutProduct(PreMessageDto $preMessageDto): bool
    {
        $preMessageDto->setMessage('aboutProduct');

        return false;
    }

    private function gotoMain(PreMessageDto $preMessageDto): bool
    {
        $preMessageDto->setMessage('gotoMain');

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
}

<?php

namespace App\Service\System\Handler\Chain;

use App\Helper;
use App\Service\Admin\Ecommerce\Product\ProductService;
use App\Service\System\Handler\Dto\CacheDto;
use App\Service\System\Handler\PreMessageDto;

class ShopProductsChain
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
    }

    public function handle(PreMessageDto $preMessageDto, ?string $content = null, CacheDto $cacheDto): bool
    {

        if ($this->checkSystemCondition($content)) {
            $event = $cacheDto->getEvent();
            $paginate = $event['paginate'];

            $products = $this->productService->getProductsByCategory($paginate['now'], 'магнитолы');

            $result = match ($content) {
                'предыдущий' => $this->prev($preMessageDto, $products, $paginate),
                'подробнее о товаре' => $this->aboutProduct($preMessageDto),
                'следующий' => $this->next($preMessageDto, $products, $paginate),
                'вернуться в главное меню' => $this->gotoMain($preMessageDto),
                default => false
            };
//            dd($paginate);

//            dd($paginate['now'], $products['paginate']['total']);
//            dd(Helper::buildPaginate($paginate['now'], $products['paginate']['total']));
            $replyMarkups = Helper::getProductNav(

            );
            $preMessageDto->setKeyBoard($replyMarkups);

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

    private function prev(PreMessageDto $preMessageDto, array $product, array &$paginate): bool
    {
        $prev = $product['paginate']['prev'];

        $paginate['now'] = $prev;

        $key = $prev - 1;
        $product = $product['products'][$key];

        $message = Helper::renderProductMessage($product);

        $photo = $product['mainImage'];

        $preMessageDto->setMessage($message);
        $preMessageDto->setPhoto($photo);

        return false;
    }

    private function next(PreMessageDto $preMessageDto, array $product, array &$paginate): bool
    {
        $next = $product['paginate']['next'];
        $paginate['now'] = $next;

        $key = $next - 1;
        $product = $product['products'][$key];

        $message = Helper::renderProductMessage($product);

        $photo = $product['mainImage'];

        $preMessageDto->setMessage($message);
        $preMessageDto->setPhoto($photo);

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

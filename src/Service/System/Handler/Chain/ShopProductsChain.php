<?php

namespace App\Service\System\Handler\Chain;

use App\Helper;
use App\Service\Admin\Ecommerce\Product\ProductService;
use App\Service\System\Handler\PreMessageDto;

class ShopProductsChain
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
    }

    public function handle(PreMessageDto $preMessageDto, ?string $content = null): bool
    {
        $replyMarkups = Helper::getProductNav();

        if ($this->checkSystemCondition($content)) {
            $preMessageDto->setKeyBoard($replyMarkups);

            $products = $this->productService->getProducts();

            return match ($content) {
                'предыдущий' => $this->prev($preMessageDto, $products),
                'подробнее о товаре' => $this->aboutProduct($preMessageDto),
                'следующий' => $this->next($preMessageDto, $products),
                'вернуться в главное меню' => $this->gotoMain($preMessageDto),
                default => false
            };
        }

        $preMessageDto->setMessage(
            'Не понимаю о чем вы... мб вам выбрать доступные варианты из меню? К примеру, вы можете посмотреть более подробную информациюю о товаре.'
        );
        $preMessageDto->setKeyBoard($replyMarkups);

        return false;
    }

    private function prev(PreMessageDto $preMessageDto, array $product): bool
    {
        $prev = $product['paginate']['prev'];
        $prev = $prev - 1;

        $product = $product['products'][$prev];

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

    private function next(PreMessageDto $preMessageDto, array $product): bool
    {
        $next = $product['paginate']['next'];
        $next = $next + 1;

        $product = $product['products'][$next];

        $message = Helper::renderProductMessage($product);

        $photo = $product['mainImage'];

        $preMessageDto->setMessage($message);
        $preMessageDto->setPhoto($photo);

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

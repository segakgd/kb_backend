<?php

namespace App\Service\System\Handler\Chain;

use App\Dto\Core\Telegram\Request\Message\MessageDto;
use App\Service\Admin\Ecommerce\Product\ProductService;
use App\Service\System\Helper;

class ShopProductsChain
{
    public function __construct(private readonly ProductService $productService)
    {
    }

    public function handle(MessageDto $messageDto, ?string $content = null): bool
    {
        $replyMarkups = Helper::getProductNav();

        if ($this->checkSystemCondition($content)) {
            $messageDto->setReplyMarkup($replyMarkups);

            $products = $this->productService->getProducts();
            $product = $products[0];

            return match ($content) {
                'предыдущий' => $this->prev($messageDto, $product),
                'подробнее о товаре' => $this->aboutProduct($messageDto, $product),
                'следующий' => $this->next($messageDto, $product),
                'вернуться в главное меню' => $this->gotoMain($messageDto),
                default => false
            };
        }

        $messageDto->setText(
            'Не понимаю о чем вы... мб вам выбрать доступные варианты из меню? К примеру, вы можете посмотреть более подробную информациюю о товаре.'
        );
        $messageDto->setReplyMarkup($replyMarkups);

        return false;
    }

    private function prev(MessageDto $messageDto, array $product): bool
    {
        $messageDto->setText('prev');

        return false;
    }

    private function aboutProduct(MessageDto $messageDto, array $product): bool
    {
        $messageDto->setText('prev');

        return false;
    }

    private function next(MessageDto $messageDto, array $product): bool
    {
        $messageDto->setText('prev');

        return false;
    }

    private function gotoMain(MessageDto $messageDto): bool
    {
        $messageDto->setText('prev');

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

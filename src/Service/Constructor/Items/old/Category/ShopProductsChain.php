<?php

namespace App\Service\Constructor\Items\old\Category;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\Common\PaginateService;
use App\Service\Constructor\Core\Dto\Responsible;
use Exception;

// use App\Service\System\Handler\Chain\AbstractChain;

readonly class ShopProductsChain // extends AbstractChain
{
    public function __construct(
        private ProductService  $productService,
        private PaginateService $paginateService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function success(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();

//        if ($cacheDto->getEvent()->getCurrentChain()->isRepeat()) {
//            $cacheDto->getEvent()->getCurrentChain()->setRepeat(false);
//
//            $content = 'first'; // todo мб возвращать на тот товар с которого ушли?
//        }

        $event = $cacheDto->getEvent();

        return match ($content) {
            'first' => $this->paginateService->first($responsible, $event->getData()),
            'предыдущий' => $this->paginateService->prev($responsible, $event->getData()),
            'следующий' => $this->paginateService->next($responsible, $event->getData()),
            'добавить в корзину' => $this->addToCart($responsible, $cacheDto),
            default => false
        };
    }

    public function fall(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $replyMarkups = KeyboardHelper::getProductNav();

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            'Не понимаю о чем вы... мб вам выбрать доступные варианты из меню? К примеру, вы можете посмотреть более подробную информациюю о товаре.',
            null,
            $replyMarkups,
        );

        $responsible->getResult()->setMessage($responsibleMessage);

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

    private function addToCart(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $productId = $cacheDto->getEvent()->getData()->getProductId();
        $responsibleMessage = MessageHelper::createResponsibleMessage('');

        $product = $this->productService->find($productId);
        $variants = $product->getVariants();

        $variantsNav = KeyboardHelper::getVariantsNav($variants);

        $responsibleMessage->setKeyBoard($variantsNav);
        $responsibleMessage->setMessage('Добавить в корзину вариант:');

        $responsible->getResult()->setMessage($responsibleMessage);

        return true;
    }
}

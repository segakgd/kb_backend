<?php

namespace App;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\Ecommerce\Product;
use App\Entity\Ecommerce\ProductVariant;
use App\Service\System\Handler\Dto\Contract\ContractMessageDto;
use Doctrine\Common\Collections\Collection;
use Exception;

class Helper
{
    public static function createSessionCache(): CacheDto
    {
        return (new CacheDto);
    }

    public static function getProductCategoryNav(array $availableCategories): array
    {
        $navCategory = [];

        foreach ($availableCategories as $availableCategory) {
            $navCategory[] = [
                'text' => $availableCategory,
            ];
        }

        return [
            $navCategory,
            [
                [
                    'text' => 'вернуться в главное меню'
                ],
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public static function buildPaginate(int $page, int $maxPage): array
    {
        if ($maxPage < $page) {
            throw new Exception('max page < page');
        }

        $prevPage = ($page > 1) ? $page - 1 : null;
        $nextPage = ($page < $maxPage) ? $page + 1 : null;

        return [
            'prev' => $prevPage,
            'now' => $page,
            'next' => $nextPage,
            'total' => $maxPage,
        ];
    }

    public static function getAvailableProductNavItems(): array
    {
        return [
            'предыдущий',
            'добавить в корзину',
            'следующий',
            'вернуться в главное меню',
        ];
    }

    public static function getVariantsNav(Collection $variants): array
    {
        $nav = [];

        /** @var ProductVariant $variant */
        foreach ($variants as $variant) {
            $nav[] = [
                [
                    'text' => $variant->getName()
                ],
            ];
        }

        return $nav;
    }

    public static function getGoToNav(): array
    {
        return [
            [
                [
                    'text' => 'вернуться обратно'
                ],
                [
                    'text' => 'вернуться в главное меню'
                ],
            ],
        ];
    }

    public static function getProductNav(?array $paginate = null): array
    {
        $nav = [
            [
                [
                    'text' => 'предыдущий'
                ],
                [
                    'text' => 'добавить в корзину'
                ],
                [
                    'text' => 'следующий'
                ],
            ],
            [
                [
                    'text' => 'вернуться в главное меню'
                ],
            ],
        ];

        if (is_array($paginate) && !isset($paginate['prev'])) {
            $nav = [
                [
                    [
                        'text' => 'добавить в корзину'
                    ],
                    [
                        'text' => 'следующий'
                    ],
                ],
                [
                    [
                        'text' => 'вернуться в главное меню'
                    ],
                ],
            ];
        }

        if (is_array($paginate) && !isset($paginate['next'])) {
            $nav = [
                [
                    [
                        'text' => 'предыдущий'
                    ],
                    [
                        'text' => 'добавить в корзину'
                    ],
                ],
                [
                    [
                        'text' => 'вернуться в главное меню'
                    ],
                ],
            ];
        }

        return $nav;
    }

    public static function translate(string $key): string
    {
        return match ($key) {
            'show.shop.products.category' => 'Приветственное сообщение, показываем доступные категории',
            'shop.products.category' => 'Показываем товары по выбранной категории',
            'shop.products' => 'Товары по выбранной катигории',
            'shop.product' => 'Просмотр конкретного продукта',
            default => $key,
        };
    }

    public static function renderProductMessage(Product $product): string
    {
        $name = $product->getName();

        $message = "ℹ️ Название: $name \n\n";

        $variants = $product->getVariants();

        /** @var ProductVariant $variant */
        foreach ($variants as $variant) {
            $name = $variant->getName();
            $price = $variant->getPrice();
            $price = $price['price'];
            $count = $variant->getCount();

            $message .= "Вариант: $name \n";
            $message .= "Цена: $price \n";
            $message .= "Доступное количество: $count \n";
            $message .= "\n";
        }

        return $message;
    }

    public static function createContractMessage(
        string $message,
        ?string $photo = null,
        ?array $keyBoard = null,
    ): ContractMessageDto {
        $contractMessage = (new ContractMessageDto())
            ->setMessage($message)
        ;

        if ($photo) {
            $contractMessage->setPhoto($photo);
        }

        if ($keyBoard) {
            $contractMessage->setKeyBoard($keyBoard);
        }

        return $contractMessage;
    }
}

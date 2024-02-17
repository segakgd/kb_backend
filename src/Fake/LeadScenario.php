<?php

namespace App\Fake;

use App\Dto\Scenario\ScenarioChainDto;
use App\Dto\Scenario\ScenarioChainItemDto;
use App\Dto\Scenario\ScenarioDto;
use App\Dto\Scenario\ScenarioKeyboardDto;
use App\Dto\Scenario\ScenarioStepDto;

class LeadScenario
{
    public function create(): array
    {
        return [
            $this->goScenario(),
            $this->getProductsByCategory(),
            $this->getPopularProducts(),
            $this->getPromoProducts(),
            $this->getCart(),
            $this->order(),
            $this->shippingEdit(),
            $this->contactsEdit(),
            $this->productEdit(),
            $this->deleteCart(),
            $this->payCart()
        ];
    }

    private function goScenario(): ScenarioDto
    {
        return (new ScenarioDto())
            ->setUUID('8e261302-d5d7-4dd0-a621-4253a01da31e')
            ->setName('Сценарий оформления заказа')
            ->setType('message')
            ->addStep(
                (new ScenarioStepDto())
                    ->setMessage(
                        'Добро пожаловать в нашем шопе. Хотите приобрести товар? Выберите одну из категорий:'
                    )
                    ->setKeyboard(
                        (new ScenarioKeyboardDto)
                            ->setReplyMarkup(
                                [
                                    [
                                        [
                                            "text" => "Товары по категориям",
                                            "target" => "8e261312-0001-4dd0-a627-4253a01da001",
                                        ],
                                        [
                                            "text" => "Популярные товары",
                                            "target" => "8e261322-0002-4dd0-a627-4253a01da002",
                                        ],
                                    ],
                                    [
                                        [
                                            "text" => "Акционные товары",
                                            "target" => "8e261332-0003-4dd0-a627-4253a01da003",
                                        ],
                                        [
                                            "requirement" => [
                                                'action' => 'not.empty',
                                                'target' => 'lead.products',
                                            ],
                                            "text" => "Моя корзина",
                                            "target" => "78bc84e3-0004-4ebe-bbb4-4253a01da004",
                                        ],
                                    ],
                                ]
                            )
                    )
            );
    }

    private function getProductsByCategory(): ScenarioDto
    {
        return (new ScenarioDto())
            ->setUUID('8e261312-0001-4dd0-a627-4253a01da001')
            ->setName('Товары по категориям')
            ->setType('message')
            ->addStep(
                (new ScenarioStepDto())
                    ->addChain(
                        (new ScenarioChainDto())
                            ->addNow(
                                (new ScenarioChainItemDto)
                                    ->setAction('show')
                                    ->setTarget('shop.products.category')
                            )
                            ->addAfter(
                                (new ScenarioChainItemDto)
                                    ->setAction('save')
                                    ->setTarget('shop.products.category')
                            )
                    )
                    ->addChain(
                        (new ScenarioChainDto())
                            ->addNow(
                                (new ScenarioChainItemDto)
                                    ->setAction('show')
                                    ->setTarget('shop.products')
                            )
                            ->addAfter(
                                (new ScenarioChainItemDto)
                                    ->setAction('save')
                                    ->setTarget('shop.products')
                            )
                    )
                    ->addChain(
                        (new ScenarioChainDto())
                            ->addNow(
                                (new ScenarioChainItemDto)
                                    ->setAction('show')
                                    ->setTarget('shop.product')
                            )
                            ->addAfter(
                                (new ScenarioChainItemDto)
                                    ->setAction('save')
                                    ->setTarget('shop.product')
                            )
                    )
            );
    }

    private function getPopularProducts(): ScenarioDto
    {
        return (new ScenarioDto())
            ->setUUID('8e261322-0002-4dd0-a627-4253a01da002')
            ->setName('Популярные товары')
            ->setType('message')
            ->addStep(
                (new ScenarioStepDto())
                    ->addChain(
                        (new ScenarioChainDto())
                            ->addNow(
                                (new ScenarioChainItemDto)
                                    ->setAction('show')
                                    ->setTarget('shop.products.popular')
                            )
                            ->addAfter(
                                (new ScenarioChainItemDto)
                                    ->setAction('save')
                                    ->setTarget('shop.products.popular')
                            )
                    )
                    ->addChain(
                        (new ScenarioChainDto())
                            ->addNow(
                                (new ScenarioChainItemDto)
                                    ->setAction('show')
                                    ->setTarget('shop.products')
                            )
                            ->addAfter(
                                (new ScenarioChainItemDto)
                                    ->setAction('save')
                                    ->setTarget('shop.products')
                            )
                    )
                    ->addChain(
                        (new ScenarioChainDto())
                            ->addNow(
                                (new ScenarioChainItemDto)
                                    ->setAction('show')
                                    ->setTarget('shop.product')
                            )
                            ->addAfter(
                                (new ScenarioChainItemDto)
                                    ->setAction('save')
                                    ->setTarget('shop.product')
                            )
                    )
            );
    }

    private function getPromoProducts(): ScenarioDto
    {
        return (new ScenarioDto())
            ->setUUID('8e261332-0003-4dd0-a627-4253a01da003')
            ->setName('Акционные товары')
            ->setType('message')
            ->addStep(
                (new ScenarioStepDto())
                    ->addChain(
                        (new ScenarioChainDto())
                            ->addNow(
                                (new ScenarioChainItemDto)
                                    ->setAction('show')
                                    ->setTarget('shop.products.promotion')
                            )
                            ->addAfter(
                                (new ScenarioChainItemDto)
                                    ->setAction('save')
                                    ->setTarget('shop.products.promotion')
                            )
                    )
                    ->addChain(
                        (new ScenarioChainDto())
                            ->addNow(
                                (new ScenarioChainItemDto)
                                    ->setAction('show')
                                    ->setTarget('shop.products')
                            )
                            ->addAfter(
                                (new ScenarioChainItemDto)
                                    ->setAction('save')
                                    ->setTarget('shop.products')
                            )
                    )
                    ->addChain(
                        (new ScenarioChainDto())
                            ->addNow(
                                (new ScenarioChainItemDto)
                                    ->setAction('show')
                                    ->setTarget('shop.product')
                            )
                            ->addAfter(
                                (new ScenarioChainItemDto)
                                    ->setAction('save')
                                    ->setTarget('shop.product')
                            )
                    )
            );
    }

    private function getCart(): ScenarioDto
    {
        return (new ScenarioDto())
            ->setUUID('78bc84e3-0004-4ebe-bbb4-4253a01da004')
            ->setName('Моя корзина')
            ->setType('message')
            ->addStep(
                (new ScenarioStepDto())
                    ->setMessage(
                        'Добро пожаловать в нашем шопе. Хотите приобрести товар? Выберите одну из категорий:'
                    )
                    ->setKeyboard(
                        (new ScenarioKeyboardDto)
                            ->setReplyMarkup(
                                [
                                    [
                                        [
                                            "requirements" => [
                                                [
                                                    'action' => 'empty',
                                                    'target' => 'cart.contact',
                                                ],
                                                [
                                                    'action' => 'empty',
                                                    'target' => 'cart.shipping',
                                                ],
                                            ],
                                            "text" => "Оформить заказ",
                                            "target" => "78bc84e3-0004-4ebe-bbb4-4253a01da005",
                                        ],
                                        [
                                            "requirements" => [
                                                [
                                                    'action' => 'not.empty',
                                                    'target' => 'cart.shipping',
                                                ],
                                            ],
                                            "text" => "Изменить доставку",
                                            "target" => "8e261322-0002-4dd0-a627-4253a01da006",
                                        ],
                                        [
                                            "requirements" => [
                                                [
                                                    'action' => 'not.empty',
                                                    'target' => 'cart.contact',
                                                ],
                                            ],
                                            "text" => "Изменить контакты",
                                            "target" => "8e261322-0002-4dd0-a627-4253a01da007",
                                        ],
                                    ],
                                    [
                                        [
                                            "requirements" => [
                                                [
                                                    'action' => 'not.empty',
                                                    'target' => 'cart.products',
                                                ],
                                            ],
                                            "text" => "Изменить продукты",
                                            "target" => "8e261332-0003-4dd0-a627-4253a01da008",
                                        ],
                                        [
                                            "requirements" => [
                                                [
                                                    'action' => 'or.not.empty',
                                                    'target' => 'cart.contact',
                                                ],
                                                [
                                                    'action' => 'or.not.empty',
                                                    'target' => 'cart.shipping',
                                                ],
                                                [
                                                    'action' => 'or.not.empty',
                                                    'target' => 'cart.products',
                                                ],
                                            ],
                                            "text" => "Удалить заказ",
                                            "target" => "8e261332-0003-4dd0-a627-4253a01da009",
                                        ],
                                        [
                                            "requirements" => [
                                                [
                                                    'action' => 'not.empty',
                                                    'target' => 'cart.contact',
                                                ],
                                                [
                                                    'action' => 'not.empty',
                                                    'target' => 'cart.shipping',
                                                ],
                                                [
                                                    'action' => 'not.empty',
                                                    'target' => 'cart.products',
                                                ],
                                            ],
                                            "text" => "Оплатить",
                                            "target" => "78bc84e3-0004-4ebe-bbb4-4253a01da010",
                                        ],
                                    ],
                                ]
                            )
                    )
            );
    }

    private function order(): ScenarioDto
    {
        return (new ScenarioDto())
            ->setUUID('78bc84e3-0004-4ebe-bbb4-4253a01da005')
            ->setName('Оформить заказ')
            ->setType('message')
            ->addStep(
                (new ScenarioStepDto())
                    ->addChain(
                        (new ScenarioChainDto())
                            ->addNow(
                                (new ScenarioChainItemDto)
                                    ->setAction('edit')
                                    ->setTarget('cart.contact')
                            )
                            ->addAfter(
                                (new ScenarioChainItemDto)
                                    ->setAction('save')
                                    ->setTarget('cart.contact')
                            )
                    )
                    ->addChain(
                        (new ScenarioChainDto())
                            ->addNow(
                                (new ScenarioChainItemDto)
                                    ->setAction('edit')
                                    ->setTarget('cart.shipping')
                            )
                            ->addAfter(
                                (new ScenarioChainItemDto)
                                    ->setAction('save')
                                    ->setTarget('cart.shipping')
                            )
                    )
            );
    }

    private function shippingEdit(): ScenarioDto
    {
        return (new ScenarioDto())
            ->setUUID('8e261322-0002-4dd0-a627-4253a01da006')
            ->setName('Изменить доставку')
            ->setType('message')
            ->addStep(
                (new ScenarioStepDto())
                    ->addChain(
                        (new ScenarioChainDto())
                            ->addNow(
                                (new ScenarioChainItemDto)
                                    ->setAction('edit')
                                    ->setTarget('cart.shipping')
                            )
                    )
            );
    }

    private function contactsEdit(): ScenarioDto
    {
        return (new ScenarioDto())
            ->setUUID('8e261322-0002-4dd0-a627-4253a01da007')
            ->setName('Изменить контакты')
            ->setType('message')
            ->addStep(
                (new ScenarioStepDto())
                    ->addChain(
                        (new ScenarioChainDto())
                            ->addNow(
                                (new ScenarioChainItemDto)
                                    ->setAction('edit')
                                    ->setTarget('cart.contact')
                            )
                    )
            );
    }

    private function productEdit(): ScenarioDto
    {
        return (new ScenarioDto())
            ->setUUID('8e261332-0003-4dd0-a627-4253a01da008')
            ->setName('Изменить продукты')
            ->setType('message')
            ->addStep(
                (new ScenarioStepDto())
                    ->addChain(
                        (new ScenarioChainDto())
                            ->addNow(
                                (new ScenarioChainItemDto)
                                    ->setAction('edit')
                                    ->setTarget('cart.product')
                            )
                    )
            );
    }

    private function deleteCart(): ScenarioDto
    {
        return (new ScenarioDto())
            ->setUUID('8e261332-0003-4dd0-a627-4253a01da009')
            ->setName('Удалить заказ')
            ->setType('message')
            ->addStep(
                (new ScenarioStepDto())
                    ->addChain(
                        (new ScenarioChainDto())
                            ->addNow(
                                (new ScenarioChainItemDto)
                                    ->setAction('delete')
                                    ->setTarget('cart')
                            )
                    )
            );
    }

    private function payCart(): ScenarioDto
    {
        return (new ScenarioDto())
            ->setUUID('78bc84e3-0004-4ebe-bbb4-4253a01da010')
            ->setName('Оплатить')
            ->setType('message')
            ->addStep(
                (new ScenarioStepDto())
                    ->addChain(
                        (new ScenarioChainDto())
                            ->addNow(
                                (new ScenarioChainItemDto)
                                    ->setAction('run')
                                    ->setTarget('cart.pay')
                            )
                    )
            );
    }
}

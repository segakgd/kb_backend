<?php

namespace App\Helper;

class EcommerceScenarioHelper
{
    public static function testScenario(): array
    {
        return [
            "UUID" => "8e261302-d5d7-4dd0-a621-4253a01da31e",
            "ownerUUID" => null,
            "name" => "Сценарий оформления заказа",
            "type" => "message",
            "contracts" => [
                [
                    "message" => "Добро пожаловать в нашем шопе. Хотите приобрести товар? Выберите одну из категорий:",
                    "keyboard" => [
                        "replyMarkup" => [
                            [
                                [
                                    "text" => "Товары по категориям",
                                    "target" => "8e261312-0001-4dd0-a627-4253a01da001"
                                ],
                                [
                                    "text" => "Популярные товары",
                                    "target" => "8e261322-0002-4dd0-a627-4253a01da002"
                                ]
                            ],
                            [
                                [
                                    "text" => "Акционные товары",
                                    "target" => "8e261332-0003-4dd0-a627-4253a01da003"
                                ],
                                [
                                    "requirement" => [
                                        "action" => "not.empty",
                                        "target" => "lead.products"
                                    ],
                                    "text" => "Моя корзина",
                                    "target" => "78bc84e3-0004-4ebe-bbb4-4253a01da004"
                                ]
                            ]
                        ],
                    ],
                    "chain" => null,
                    "attached" => null
                ]
            ]
        ];
    }

    public static function getProductScenario(): array
    {
        return [
            [
                [
                    "UUID" => "8e261312-0001-4dd0-a627-4253a01da001",
                    "ownerUUID" => null,
                    "name" => "Товары по категориям",
                    "type" => "message",
                    "contracts" => static::getProductByCategory()
                ],
                [
                    "UUID" => "8e261322-0002-4dd0-a627-4253a01da002",
                    "ownerUUID" => null,
                    "name" => "Популярные товары",
                    "type" => "message",
                    "contracts" => static::getPopularProducts()
                ],
                [
                    "UUID" => "8e261332-0003-4dd0-a627-4253a01da003",
                    "ownerUUID" => null,
                    "name" => "Акционные товары",
                    "type" => "message",
                    "contracts" => static::getPromoProducts()
                ],
                [
                    "UUID" => "78bc84e3-0004-4ebe-bbb4-4253a01da004",
                    "ownerUUID" => null,
                    "name" => "Моя корзина",
                    "type" => "message",
                    "contracts" => static::getCartContracts()
                ],
                [
                    "UUID" => "78bc84e3-0004-4ebe-bbb4-4253a01da005",
                    "ownerUUID" => null,
                    "name" => "Оформить заказ",
                    "type" => "message",
                    "contracts" => static::getPlaceAnOrderContracts()
                ],
                [
                    "UUID" => "8e261322-0002-4dd0-a627-4253a01da006",
                    "ownerUUID" => null,
                    "name" => "Изменить доставку",
                    "type" => "message",
                    "contracts" => static::changeShippingContracts()
                ],
                [
                    "UUID" => "8e261322-0002-4dd0-a627-4253a01da007",
                    "ownerUUID" => null,
                    "name" => "Изменить контакты",
                    "type" => "message",
                    "contracts" => static::changeContactsContracts()
                ],
                [
                    "UUID" => "8e261332-0003-4dd0-a627-4253a01da008",
                    "ownerUUID" => null,
                    "name" => "Изменить продукты",
                    "type" => "message",
                    "contracts" => static::changeProductsContracts()
                ],
                [
                    "UUID" => "8e261332-0003-4dd0-a627-4253a01da009",
                    "ownerUUID" => null,
                    "name" => "Удалить заказ",
                    "type" => "message",
                    "contracts" => static::removeOrderContracts()
                ],
                [
                    "UUID" => "78bc84e3-0004-4ebe-bbb4-4253a01da010",
                    "ownerUUID" => null,
                    "name" => "Оплатить",
                    "type" => "message",
                    "contracts" => static::paymentContracts()
                ]
            ]
        ];
    }

    private static function removeOrderContracts(): array
    {
        return [
            [
                "message" => null,
                "keyboard" => null,
                "chain" => [
                    [
                        "target" => "cart.pay",
                        "finish" => false
                    ]
                ],
                "attached" => null
            ]
        ];
    }

    private static function paymentContracts(): array
    {
        return [
            [
                "message" => null,
                "keyboard" => null,
                "chain" => [
                    [
                        "target" => "cart.clear",
                        "finish" => false
                    ]
                ],
                "attached" => null
            ]
        ];
    }

    private static function getPromoProducts(): array
    {
        return [
            [
                "message" => null,
                "keyboard" => null,
                "chain" => [
                    "message" => null,
                    "keyboard" => null,
                    "chain" => [
                        [
                            "target" => "shop.products.promotion",
                            "finish" => false
                        ],
                        [
                            "target" => "shop.products",
                            "finish" => false
                        ],
                        [
                            "target" => "shop.product",
                            "finish" => false
                        ]
                    ],
                    "attached" => null
                ],
                "attached" => null
            ]
        ];
    }

    private static function getProductByCategory(): array
    {
        return [
            [
                "message" => null,
                "keyboard" => null,
                "chain" => [
                    [
                        "target" => "show.shop.products.category",
                        "finish" => false
                    ],
                    [
                        "target" => "shop.products.category",
                        "finish" => false
                    ],
                    [
                        "target" => "shop.products",
                        "finish" => false
                    ],
                    [
                        "target" => "shop.variant",
                        "finish" => false
                    ],
                    [
                        "target" => "shop.variant.count",
                        "finish" => false
                    ],
                    [
                        "target" => "shop.final",
                        "finish" => false
                    ]
                ],
                "attached" => null
            ]
        ];
    }

    private static function getPopularProducts(): array
    {
        return [
            [
                "message" => null,
                "keyboard" => null,
                "chain" => [
                    [
                        "message" => null,
                        "keyboard" => null,
                        "chain" => [
                            [
                                "target" => "shop.products.popular",
                                "finish" => false
                            ],
                            [
                                "target" => "shop.products",
                                "finish" => false
                            ],
                            [
                                "target" => "shop.product",
                                "finish" => false
                            ]
                        ],
                        "attached" => null
                    ]
                ],
                "attached" => null
            ]
        ];
    }

    private static function getCartContracts(): array
    {
        return [
            [
                "message" => "Добро пожаловать в нашем шопе. Хотите приобрести товар? Выберите одну из категорий:",
                "keyboard" => [
                    "replyMarkup" => [
                        [
                            [
                                "requirements" => [
                                    [
                                        "action" => "empty",
                                        "target" => "cart.contact"
                                    ],
                                    [
                                        "action" => "empty",
                                        "target" => "cart.shipping"
                                    ]
                                ],
                                "text" => "Оформить заказ",
                                "target" => "78bc84e3-0004-4ebe-bbb4-4253a01da005"
                            ],
                            [
                                "requirements" => [
                                    [
                                        "action" => "not.empty",
                                        "target" => "cart.shipping"
                                    ]
                                ],
                                "text" => "Изменить доставку",
                                "target" => "8e261322-0002-4dd0-a627-4253a01da006"
                            ],
                            [
                                "requirements" => [
                                    [
                                        "action" => "not.empty",
                                        "target" => "cart.contact"
                                    ]
                                ],
                                "text" => "Изменить контакты",
                                "target" => "8e261322-0002-4dd0-a627-4253a01da007"
                            ]
                        ],
                        [
                            [
                                "requirements" => [
                                    [
                                        "action" => "not.empty",
                                        "target" => "cart.products"
                                    ]
                                ],
                                "text" => "Изменить продукты",
                                "target" => "8e261332-0003-4dd0-a627-4253a01da008"
                            ],
                            [
                                "requirements" => [
                                    [
                                        "action" => "or.not.empty",
                                        "target" => "cart.contact"
                                    ],
                                    [
                                        "action" => "or.not.empty",
                                        "target" => "cart.shipping"
                                    ],
                                    [
                                        "action" => "or.not.empty",
                                        "target" => "cart.products"
                                    ]
                                ],
                                "text" => "Удалить заказ",
                                "target" => "8e261332-0003-4dd0-a627-4253a01da009"
                            ],
                            [
                                "requirements" => [
                                    [
                                        "action" => "not.empty",
                                        "target" => "cart.contact"
                                    ],
                                    [
                                        "action" => "not.empty",
                                        "target" => "cart.shipping"
                                    ],
                                    [
                                        "action" => "not.empty",
                                        "target" => "cart.products"
                                    ]
                                ],
                                "text" => "Оплатить",
                                "target" => "78bc84e3-0004-4ebe-bbb4-4253a01da010"
                            ]
                        ]
                    ],
                ],
                "chain" => null,
                "attached" => null
            ]
        ];
    }

    private static function getPlaceAnOrderContracts(): array
    {
        return [
            [
                "message" => null,
                "keyboard" => null,
                "chain" => [
                    [
                        "target" => "cart.view.contact",
                        "finish" => false
                    ],
                    [
                        "target" => "cart.contact",
                        "finish" => false
                    ],
                    [
                        "target" => "cart.phone.contact",
                        "finish" => false
                    ],
                    [
                        "target" => "cart.shipping",
                        "finish" => false
                    ],
                    [
                        "target" => "cart.shipping.country",
                        "finish" => false
                    ],
                    [
                        "target" => "cart.shipping.region",
                        "finish" => false
                    ],
                    [
                        "target" => "cart.shipping.city",
                        "finish" => false
                    ],
                    [
                        "target" => "cart.shipping.street",
                        "finish" => false
                    ],
                    [
                        "target" => "cart.shipping.numberHome",
                        "finish" => false
                    ],
                    [
                        "target" => "cart.shipping.entrance",
                        "finish" => false
                    ],
                    [
                        "target" => "cart.shipping.apartment",
                        "finish" => false
                    ],
                    [
                        "target" => "cart.save",
                        "finish" => false
                    ],
                    [
                        "target" => "cart.finish",
                        "finish" => false
                    ]
                ],
                "attached" => null
            ]
        ];
    }

    private static function changeShippingContracts(): array
    {
        return [
            [
                "message" => null,
                "keyboard" => null,
                "chain" => [
                    [
                        "target" => "edit.cart.shipping",
                        "finish" => false
                    ]
                ],
                "attached" => null
            ]
        ];
    }

    private static function changeContactsContracts(): array
    {
        return [
            [
                "message" => null,
                "keyboard" => null,
                "chain" => [
                    [
                        "target" => "edit.cart.contact",
                        "finish" => false
                    ]
                ],
                "attached" => null
            ]
        ];
    }

    private static function changeProductsContracts(): array
    {
        return [
            [
                "message" => null,
                "keyboard" => null,
                "chain" => [
                    [
                        "target" => "edit.cart.product",
                        "finish" => false
                    ]
                ],
                "attached" => null
            ]
        ];
    }
}

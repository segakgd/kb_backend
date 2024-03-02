<?php

namespace App\Controller;

use App\Dto\Scenario\ScenarioAttachedDto;
use App\Dto\Scenario\ScenarioChainDto;
use App\Dto\Scenario\ScenarioChainItemDto;
use App\Dto\Scenario\ScenarioDto;
use App\Dto\Scenario\ScenarioKeyboardDto;
use App\Dto\Scenario\ScenarioStepDto;
use App\Fake\LeadScenario;
use App\Repository\Ecommerce\ProductCategoryEntityRepository;
use App\Repository\Ecommerce\ProductEntityRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MainController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly LeadScenario $leadScenario,
        private readonly ProductEntityRepository $productCategoryEntityRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    #[Route('/', name: 'app_main', methods: ['GET'])]
    public function main(): JsonResponse
    {
        // todo надо расширить навигацию... добавить возможность показывать кнопку относительно контента в сессии
        // todo ввести понятие или (нужны вентили на уровне цепи)

        $scenario = [
            "name" => "Цепочка событий",
            "scenario" => [
                $this->leadScenario->create(),
//                $this->getLeadScenario(),
//                $this->getBookingScenario(),
//                $this->getScenarioPhone(),
//                $this->getScenarioName(),
//                $this->getDownloadDoc(),
//                $this->getLink()
            ]
        ];

        dd($scenario, $this->serializer->serialize($scenario, 'json'));

        return new JsonResponse(
            $this->serializer->normalize($scenario, 'json')
        );
    }


    private function getLeadScenario(): array
    {
        return [
            (new ScenarioDto())
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
                                                "requirement" => [
                                                    'action' => 'empty.not',
                                                    'target' => 'contacts',
                                                ],
                                                "target" => "8e261312-0001-4dd0-a627-4253a01da39e",
                                            ],
                                            [
                                                "text" => "Популярные товары",
                                                "target" => "8e261322-0002-4dd0-a627-4253a01da39e",
                                            ],
                                        ],
                                        [
                                            [
                                                "text" => "Акционные товары",
                                                "target" => "8e261332-0003-4dd0-a627-4253a01da39e",
                                            ],
                                            [
                                                "text" => "Моя корзина",
                                                "target" => "78bc84e3-0004-4ebe-bbb4-1c3e093e19a2",
                                            ],
                                        ],
                                    ]
                                )
                        )
                ),
            (new ScenarioDto())
                ->setUUID('8e261312-0001-4dd0-a627-4253a01da39e')
                ->setName('Товары по категориям')
                ->setType('message')
                ->addStep(
                    (new ScenarioStepDto())
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('show')
                                        ->setTarget('shop.products.category') // выводим категории: магнитолы, динамики
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('shop.products.category')
                                )
                        )
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('show')
                                        ->setTarget('shop.products') // Магнитолы - jac, loop, к категориям
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('shop.products')
                                )
                        )
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('show')
                                        ->setTarget(
                                            'shop.product'
                                        ) // (любой товар) - выводим товары, предлагаем выйти обратно к динамикам, или на главную и пагинацию, или положить товар в корзину
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('shop.product')
                                )
                        )
                ),
            (new ScenarioDto())
                ->setUUID('8e261312-0002-4dd0-a627-4253a01da39e')
                ->setName('Популярные товары')
                ->setType('message')
                ->addStep(
                    (new ScenarioStepDto())
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('show')
                                        ->setTarget('shop.products.popular') // выводим категории: магнитолы, динамики
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('shop.products.popular')
                                )
                        )
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('show')
                                        ->setTarget('shop.products') // Магнитолы - jac, loop, к категориям
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('shop.products')
                                )
                        )
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('show')
                                        ->setTarget(
                                            'shop.product'
                                        ) // (любой товар) - выводим товары, предлагаем выйти обратно к динамикам, или на главную и пагинацию, или положить товар в корзину
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('shop.product')
                                )
                        )
                ),
            (new ScenarioDto())
                ->setUUID('8e261312-0003-4dd0-a627-4253a01da39e')
                ->setName('Акционные товары')
                ->setType('message')
                ->addStep(
                    (new ScenarioStepDto())
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('show')
                                        ->setTarget('shop.products.promotion') // выводим категории: магнитолы, динамики
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('shop.products.promotion')
                                )
                        )
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('show')
                                        ->setTarget('shop.products') // Магнитолы - jac, loop, к категориям
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('shop.products')
                                )
                        )
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('show')
                                        ->setTarget(
                                            'shop.product'
                                        ) // (любой товар) - выводим товары, предлагаем выйти обратно к динамикам, или на главную и пагинацию, или положить товар в корзину
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('shop.product')
                                )
                        )
                ),
            (new ScenarioDto())
                ->setUUID('8e261312-0004-4dd0-a627-4253a01da39e')
                ->setName('Моя корзина')
                ->setType('message')
                ->addStep(
                    (new ScenarioStepDto())
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('show')
                                        ->setTarget(
                                            'cart.info'
                                        ) // В вашей корзине сейчас то сё, пятое десятое... + выводим кнопки начать оплату (если есть товары)
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('brake.if') // todo тут не совсем понятно...
                                        ->setTarget('cart.info')
                                )
                        )
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('edit')
                                        ->setTarget('contact.firstName') // Введите свое имя
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('contact.firstName') // Ваше имя...
                                )
                        )
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('edit')
                                        ->setTarget('shipping.type') // доставка куртером или самовывоз
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('shipping.type') // Ваш адрес...
                                )
                        )
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addBefore(
                                    (new ScenarioChainItemDto)
                                        ->setAction(
                                            'allowed.if'
                                        ) // разрешён если выполняется shipping.type в каком-то значении
                                        ->setTarget('shipping.type')
                                )
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('edit')
                                        ->setTarget('contact.address') // Введите свой адрес
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('contact.address') // Ваш адрес...
                                )
                        )
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('run')
                                        ->setTarget('payment') // Введите свой адрес
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('payment')
                                )
                        )
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('show')
                                        ->setTarget('payment.info') // Введите свой адрес
                                )
                        )

                ),
        ];
    }

    private function getBookingScenario(): array
    {
        return [
            (new ScenarioDto())
                ->setUUID('17f3e9db-5d98-416d-80f7-c5f0902abeb6')
                ->setName('Бронирование')
                ->setType('message')
                ->addStep(
                    (new ScenarioStepDto())
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('edit')
                                        ->setTarget('booking.month') // Выберите месяц
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('booking.month') // Вы выберали месяц МАРТ
                                )
                        )
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('edit')
                                        ->setTarget('booking.bay') // Выберите день
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('booking.bay') // Вы выбрали 30 число
                                )
                        )
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('edit')
                                        ->setTarget('booking.time') // Выберите время
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('booking.time') // Вы выбрали верям 13:00
                                )
                        )
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('edit')
                                        ->setTarget('contact.address') // Введите свой адрес
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('contact.address') // Ваш адрес...
                                )
                        )
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('edit')
                                        ->setTarget('contact.firstName') // Введите свое имя
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('contact.firstName') // Ваше имя...
                                )
                        )
                )
                ->addStep(
                    (new ScenarioStepDto())
                        ->setMessage('Оформить?')
                        ->setKeyboard(
                            (new ScenarioKeyboardDto())
                                ->setReplyMarkup(
                                    [
                                        [
                                            [
                                                "text" => "да",
                                                "target" => "8e261302-d5d7-4dd0-a627-4253a01da39e",
                                            ],
                                            [
                                                "text" => "нет",
                                                "target" => "78bc86e3-b0f9-4ebe-bbb4-1c3e093e19a2",
                                            ],
                                        ],
                                    ]
                                )
                        )
                ),
            $this->getBookingScenario2(),
            $this->getBookingScenario3(),
        ];
    }

    private function getBookingScenario2(): ScenarioDto
    {
        $step = (new ScenarioStepDto())
            ->setMessage('Отлично, ваше бронирование поступило на рассмотрение администратору');

        return (new ScenarioDto())
            ->setUUID('8e261302-d5d7-4dd0-a627-4253a01da39e')
            ->setName('Да')
            ->setType('message')
            ->addStep($step);
    }

    private function getBookingScenario3(): ScenarioDto
    {
        $step = (new ScenarioStepDto())
            ->setMessage('Отлично, ваше бронирование отменено');

        return (new ScenarioDto())
            ->setUUID('78bc86e3-b0f9-4ebe-bbb4-1c3e093e19a2')
            ->setName('Нет')
            ->setType('message')
            ->addStep($step);
    }

    public function getScenarioPhone(): array
    {
        return [
            (new ScenarioDto())
                ->setUUID('8e261302-d5d7-4dd0-a627-4253a01da31e')
                ->setName('Сценарий оформления заявки(телефон)')
                ->setType('message')
                ->addStep(
                    (new ScenarioStepDto())
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('edit')
                                        ->setTarget('contact.phone') // Пришлите свой телефон
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('contact.phone') // Ваш телевон: 89999999999
                                )
                        )
                )
        ];
    }

    public function getScenarioName(): array
    {
        return [
            (new ScenarioDto())
                ->setUUID('8e261302-d5d7-4dd0-a627-4254a01da31e')
                ->setName('Сценарий оформления заявки(фио)')
                ->setType('message')
                ->addStep(
                    (new ScenarioStepDto())
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('edit')
                                        ->setTarget('contact.firstName')
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('contact.firstName')
                                )
                        )
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('edit')
                                        ->setTarget('contact.lastName')
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('contact.lastName')
                                )
                        )
                        ->addChain(
                            (new ScenarioChainDto)
                                ->addNow(
                                    (new ScenarioChainItemDto)
                                        ->setAction('edit')
                                        ->setTarget('contact.middleName')
                                )
                                ->addAfter(
                                    (new ScenarioChainItemDto)
                                        ->setAction('save')
                                        ->setTarget('contact.middleName')
                                )
                        )
                )
        ];
    }

    public function getDownloadDoc(): array
    {
        return [
            (new ScenarioDto())
                ->setUUID('8e261302-d5d7-2dd0-a627-4254a01da31e')
                ->setName('Сценарий скачивания документа')
                ->setType('message')
                ->addStep(
                    (new ScenarioStepDto())
                        ->setMessage('О! Какой документ ты хочешь скачать?')
                        ->setKeyboard(
                            (new ScenarioKeyboardDto)
                                ->setReplyMarkup(
                                    [
                                        [
                                            [
                                                "text" => "Рассказ о котике в txt",
                                                "target" => "8e261312-d5d7-4dd0-a627-4253a01da39e",
                                            ],
                                            [
                                                "text" => "Рассказ о котике в docx",
                                                "target" => "8e261322-d5d7-4dd0-a627-4253a01da39e",
                                            ],
                                            [
                                                "text" => "Фото котика",
                                                "target" => "8e261332-d5d7-4dd0-a627-4253a01da39e",
                                            ],
                                            [
                                                "text" => "Видео котика",
                                                "target" => "78bc84e3-b0f9-4ebe-bbb4-1c3e093e19a2",
                                            ],
                                        ],
                                    ]
                                )
                        )
                ),
            (new ScenarioDto())
                ->setUUID('8e261312-d5d7-4dd0-a627-4253a01da39e')
                ->setName('Рассказ о котике в txt')
                ->setType('message')
                ->addStep(
                    (new ScenarioStepDto())
                        ->setMessage('Ваш документ')
                        ->setAttached(
                            (new ScenarioAttachedDto())
                                ->setDocument('document')
                        )
                ),
            (new ScenarioDto())
                ->setUUID('8e261322-d5d7-4dd0-a627-4253a01da39e')
                ->setName('Рассказ о котике в docx')
                ->setType('message')
                ->addStep(
                    (new ScenarioStepDto())
                        ->setMessage('Ваш документ')
                        ->setAttached(
                            (new ScenarioAttachedDto())
                                ->setDocument('document')
                        )
                ),
            (new ScenarioDto())
                ->setUUID('8e261332-d5d7-4dd0-a627-4253a01da39e')
                ->setName('Фото котика')
                ->setType('message')
                ->addStep(
                    (new ScenarioStepDto())
                        ->setMessage('Ваше вото котика')
                        ->setAttached(
                            (new ScenarioAttachedDto())
                                ->setDocument('document')
                        )
                ),
            (new ScenarioDto())
                ->setUUID('78bc84e3-b0f9-4ebe-bbb4-1c3e093e19a2')
                ->setName('Видео котика')
                ->setType('message')
                ->addStep(
                    (new ScenarioStepDto())
                        ->setMessage('Ваше видео котика')
                        ->setAttached(
                            (new ScenarioAttachedDto())
                                ->setDocument('document')
                        )
                ),
        ];
    }

    public function getLink(): array
    {
        return [
            (new ScenarioDto())
                ->setUUID('4e261302-d5d7-2dd0-a627-4254a01da31e')
                ->setName('Сценарий получения ссылки')
                ->setType('message')
                ->addStep(
                    (new ScenarioStepDto())
                        ->setMessage('Ооо ты хочешь ссылку на тот самый ресурс? 🍁')
                        ->setKeyboard(
                            (new ScenarioKeyboardDto)
                                ->setReplyMarkup(
                                    [
                                        [
                                            [
                                                "text" => "Ну да, хочу",
                                                "target" => "8e261312-d5d7-4dd0-a627-4253a01da39e",
                                            ],
                                        ],
                                    ]
                                )
                        )
                ),
            (new ScenarioDto())
                ->setUUID('8e261312-d5d7-4dd0-a627-4253a01da39e')
                ->setName('Ну да, хочу')
                ->setType('message')
                ->addStep(
                    (new ScenarioStepDto())
                        ->setMessage('А 18 тебе есть? 🍭')
                        ->setKeyboard(
                            (new ScenarioKeyboardDto)
                                ->setReplyMarkup(
                                    [
                                        [
                                            [
                                                "text" => "Да, мне есть 18",
                                                "target" => "8e261312-d5d7-4dd0-a627-4253a02da39e",
                                            ],
                                            [
                                                "text" => "Нет, нету",
                                                "target" => "8e261312-d5d7-4dd0-a627-4253a03da39e",
                                            ],
                                        ],
                                    ]
                                )
                        )
                ),
            (new ScenarioDto())
                ->setUUID('8e261312-d5d7-4dd0-a627-4253a02da39e')
                ->setName('Да, мне есть 18')
                ->setType('message')
                ->addStep(
                    (new ScenarioStepDto())
                        ->setMessage('Вот твоя ссылка бро! 🍒')
                        ->setKeyboard(
                            (new ScenarioKeyboardDto)
                                ->setReplyMarkup(
                                    [
                                        [
                                            [
                                                "text" => "Вернуться в главное меню",
                                                "target" => "8e261312-d5d7-4dd0-a627-4253a01da39e",
                                            ],
                                        ],
                                    ]
                                )
                        )
                        ->setAttached(
                            (new ScenarioAttachedDto())
                                ->setLink('https://yandex.ru')
                        )
                ),
            (new ScenarioDto())
                ->setUUID('8e261312-d5d7-4dd0-a627-4253a03da39e')
                ->setName('Нет, нету')
                ->setType('message')
                ->addStep(
                    (new ScenarioStepDto())
                        ->setMessage('Тогда ничем не могу помочь 🌚')
                        ->setKeyboard(
                            (new ScenarioKeyboardDto)
                                ->setReplyMarkup(
                                    [
                                        [
                                            [
                                                "text" => "Вернуться в главное меню",
                                                "target" => "8e761312-d5d7-4dd0-a627-4253a01da39e",
                                            ],
                                        ],
                                    ]
                                )
                        )
                ),
        ];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Scenario\ScenarioTemplate;
use App\Entity\User\Bot;
use App\Entity\User\Enum\ProjectStatusEnum;
use App\Entity\User\Project;
use App\Entity\User\User;
use App\Service\Common\Bot\Enum\BotTypeEnum;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class ProjectFixtures extends Fixture implements OrderedFixtureInterface
{
    private const ADMIN_EMAIL = 'admin@test.email';

    public function getOrder(): int
    {
        return 3;
    }

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $userRepository = $manager->getRepository(User::class);

        $user = $userRepository->findOneBy(
            [
                'email' => static::ADMIN_EMAIL,
            ]
        );

        if (is_null($user)) {
            throw new Exception('Не нашёл админа');
        }

        $project = (new Project())
            ->setName('Сгенерированный проект')
            ->setStatus(ProjectStatusEnum::Active)
            ->addUser($user)
            ->setActiveFrom(new DateTimeImmutable())
            ->setActiveTo(new DateTimeImmutable('12.12.2025'));

        $manager->persist($project);
        $manager->flush();

        $bot = (new Bot())
            ->setName('Сгенерированный бот')
            ->setType(BotTypeEnum::Telegram)
            ->setToken('token:00000000000000000000')
            ->setProjectId($project->getId())
            ->setActive(true)
            ->setWebhookUri('local');

        $manager->persist($bot);
        $manager->flush();

        $scenario = (new ScenarioTemplate())
            ->setName('Тестовый сценарий')
            ->setProjectId($project->getId())
            ->setScenario($this->getScenario());

        $manager->persist($scenario);
        $manager->flush();
    }

    private function getScenario(): array
    {
        return [
            [
                'UUID'     => null,
                'name'     => '/start',
                'type'     => 'command',
                'alias'    => 'start',
                'contract' => [
                    'message'  => 'Приветствуем вас в нашем тестовом боте! Здесь вы можете пройти тестирование функционала, связанного с категориями товаров, акциями и предложениями. При помощи нашего бота вы сможете проверить работу различных возможностей без каких-либо финансовых обязательств. Для вашего удобства доступна также функция тестовой оплаты, позволяющая понять, как проходит процесс оплаты товаров. Наслаждайтесь использованием нашего бота и убедитесь в его эффективности для вашего бизнеса!',
                    'keyboard' => [
                        'replyMarkup' => [
                            [
                                [
                                    'text' => 'На главную',
                                ],
                            ],
                        ],
                    ],
                    'action'   => null,
                    'attached' => null,
                ],
            ],
            [
                'UUID'     => null,
                'name'     => 'На главную',
                'type'     => 'message',
                'alias'    => 'main',
                'contract' => [
                    'message'  => 'Вот что сейчас доступно из функционала:',
                    'keyboard' => [
                        'replyMarkup' => [
                            [
                                [
                                    'text' => 'Товары по категориям',
                                ],
                            ],
                            [
                                [
                                    'text' => 'Моя корзина',
                                ],
                            ],
                        ],
                    ],
                    'action'   => null,
                    'attached' => null,
                ],
            ],
            [
                'UUID'     => null,
                'name'     => 'Товары по категориям',
                'type'     => 'message',
                'alias'    => null,
                'contract' => [
                    'message'  => null,
                    'keyboard' => null,
                    'action'   => [
                        [
                            'target' => 'start.action',
                            'finish' => false,
                        ],
                        [
                            'target' => 'product.category.action',
                            'finish' => false,
                        ],
                        [
                            'target' => 'products.by.category.action',
                            'finish' => false,
                        ],
                        [
                            'target' => 'variants.product.action',
                            'finish' => false,
                        ],
                        [
                            'target' => 'variant.product.action',
                            'finish' => false,
                        ],
                        [
                            'target' => 'finish.action',
                            'finish' => false,
                        ],
                    ],
                    'attached' => null,
                ],
            ],
            [
                'UUID'     => null,
                'name'     => 'Моя корзина',
                'type'     => 'message',
                'alias'    => 'cart',
                'contract' => [
                    'message'  => 'Добро пожаловать в нашем шопе. Хотите приобрести товар? Выберите одну из категорий:',
                    'keyboard' => null,
                    'action'   => [
                        [
                            'target' => 'cart.start.action',
                            'finish' => false,
                        ],
                        [
                            'target' => 'cart.finish.action',
                            'finish' => false,
                        ],
                    ],
                    'attached' => null,
                ],
            ],
            [
                'UUID'     => null,
                'name'     => 'Оформить заказ',
                'type'     => 'message',
                'alias'    => null,
                'contract' => [
                    'message'  => null,
                    'keyboard' => null,
                    'action'   => [
                        [
                            'target' => 'order.greeting.action',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.contacts.full-name.action',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.contacts.phone.action',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.shipping.switch',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.shipping.action',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.finish.action',
                            'finish' => false,
                        ],
                    ],
                    'attached' => null,
                ],
            ],
            [
                'UUID'     => null,
                'name'     => 'Изменить доставку',
                'type'     => 'message',
                'alias'    => null,
                'contract' => [
                    'message'  => null,
                    'keyboard' => null,
                    'action'   => [
                        [
                            'target' => 'order.shipping-change.greeting.action',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.shipping-change.action',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.shipping-change.finish.action',
                            'finish' => false,
                        ],
                    ],
                    'attached' => null,
                ],
            ],
            [
                'UUID'     => null,
                'name'     => 'Изменить контакты',
                'type'     => 'message',
                'alias'    => null,
                'contract' => [
                    'message'  => null,
                    'keyboard' => null,
                    'action'   => [
                        [
                            'target' => 'order.contacts-change.greeting.action',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.contacts-change.full-name.action',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.contacts-change.phone.action',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.contacts-change.finish.action',
                            'finish' => false,
                        ],
                    ],
                    'attached' => null,
                ],
            ],
            [
                'UUID'     => null,
                'name'     => 'Изменить заказ',
                'type'     => 'message',
                'alias'    => null,
                'contract' => [
                    'message'  => null,
                    'keyboard' => null,
                    'action'   => [
                        [
                            'target' => 'order.change.greeting.action',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.change.finish.action',
                            'finish' => false,
                        ],
                    ],
                    'attached' => null,
                ],
            ],
            [
                'UUID'     => null,
                'name'     => 'Удалить заказ',
                'type'     => 'message',
                'alias'    => null,
                'contract' => [
                    'message'  => null,
                    'keyboard' => null,
                    'action'   => [
                        [
                            'target' => 'order.remove.greeting.action',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.remove.approve.action',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.remove.finish.action',
                            'finish' => false,
                        ],
                    ],
                    'attached' => null,
                ],
            ],
            [
                'UUID'     => null,
                'name'     => 'Оплатить',
                'type'     => 'message',
                'alias'    => null,
                'contract' => [
                    'message'  => null,
                    'keyboard' => null,
                    'action'   => [
                    ],
                    'attached' => null,
                ],
            ],
        ];
    }
}

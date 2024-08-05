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
                    'chain'    => null,
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
                    'chain'    => null,
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
                    'chain'    => [
                        [
                            'target' => 'start.chain',
                            'finish' => false,
                        ],
                        [
                            'target' => 'product.category.chain',
                            'finish' => false,
                        ],
                        [
                            'target' => 'products.by.category.chain',
                            'finish' => false,
                        ],
                        [
                            'target' => 'variants.product.chain',
                            'finish' => false,
                        ],
                        [
                            'target' => 'variant.product.chain',
                            'finish' => false,
                        ],
                        [
                            'target' => 'finish.chain',
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
                    'chain'    => [
                        [
                            'target' => 'cart.start.chain',
                            'finish' => false,
                        ],
                        [
                            'target' => 'cart.finish.chain',
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
                    'chain'    => [
                        [
                            'target' => 'order.greeting.chain',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.contacts.full-name.chain',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.contacts.phone.chain',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.shipping.switch',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.shipping.chain',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.finish.chain',
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
                    'chain'    => [
                        [
                            'target' => 'order.shipping-change.greeting.chain',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.shipping-change.chain',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.shipping-change.finish.chain',
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
                    'chain'    => [
                        [
                            'target' => 'order.contacts-change.greeting.chain',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.contacts-change.full-name.chain',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.contacts-change.phone.chain',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.contacts-change.finish.chain',
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
                    'chain'    => [
                        [
                            'target' => 'order.change.greeting.chain',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.change.finish.chain',
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
                    'chain'    => [
                        [
                            'target' => 'order.remove.greeting.chain',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.remove.approve.chain',
                            'finish' => false,
                        ],
                        [
                            'target' => 'order.remove.finish.chain',
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
                    'chain'    => [
                    ],
                    'attached' => null,
                ],
            ],
        ];
    }
}

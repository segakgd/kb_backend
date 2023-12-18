<?php

namespace App\Tests\Functional\Admin\Lead;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class CreateControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;

    /**
     * @dataProvider positive
     *
     * @throws Exception
     */
    public function testViewAll(array $requestContent)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);

        $client->loginUser($user);

        $client->request(
            'POST',
            '/api/admin/project/'. $project->getId() .'/lead/',
            [],
            [],
            [],
            json_encode($requestContent)
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());

        // todo когда будет готова реализация - проверить изменения в базе
    }

    private function positive(): iterable
    {
        yield [
            [
                'contacts' => [
                    [
                        'type' => 'mail',
                        'name' => 'Почта',
                        'value' => 'mail@mail.fake',
                    ]
                ],
                'fields' => [
                    [
                        'type' => 'phone',
                        'name' => 'Добавочный телефон',
                        'value' => '2396',
                    ],
                    [
                        'type' => 'text',
                        'name' => 'Комментарий',
                        'value' => 'Мой комментарий',
                    ],
                ],
                'order' => [
                    'products' => [
                        [
                            'variants' => [
                                [
                                    'id' => 12,
                                    'count' => 1,
                                    'price' => 10000,
                                ]
                            ],
                            'totalCount' => 2,
                            'totalAmount' => 20000,
                        ]
                    ],
                    'shipping' => [
                        'id' => 1,
                        'totalAmount' => 1000,
                    ],
                    'promotions' => [
                        [
                            'id' => 1,
                            'totalAmount' => 1000,
                        ]
                    ],
                ],
            ],
        ];
    }
}

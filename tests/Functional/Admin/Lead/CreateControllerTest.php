<?php

namespace App\Tests\Functional\Admin\Lead;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
class CreateControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;

    /**
     * @dataProvider positive
     *
     * @throws Exception
     */
    public function test(array $requestContent)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            method: 'POST',
            uri: '/api/admin/project/' . $project->getId() . '/lead/',
            content: json_encode($requestContent)
        );

        $this->assertEquals(
            expected: Response::HTTP_NO_CONTENT,
            actual: $client->getResponse()->getStatusCode(),
            message: $client->getResponse()->getContent()
        );

        // todo когда будет готова реализация - проверить изменения в базе
    }

    private function positive(): iterable
    {
        yield [
            [
                'contacts' => [
                    [
                        'type'  => 'mail',
                        'name'  => 'Почта',
                        'value' => 'mail@mail.fake',
                    ],
                ],
                'fields' => [
                    [
                        'type'  => 'phone',
                        'name'  => 'Добавочный телефон',
                        'value' => '2396',
                    ],
                    [
                        'type'  => 'text',
                        'name'  => 'Комментарий',
                        'value' => 'Мой комментарий',
                    ],
                ],
                'order' => [
                    'products' => [
                        [
                            'variants' => [
                                [
                                    'id'    => 12,
                                    'count' => 1,
                                    'price' => 10000,
                                ],
                            ],
                            'totalCount'  => 2,
                            'totalAmount' => 20000,
                        ],
                    ],
                    'shipping' => [
                        'id'          => 1,
                        'totalAmount' => 1000,
                    ],
                    'promotions' => [
                        [
                            'id'          => 1,
                            'totalAmount' => 1000,
                        ],
                    ],
                ],
            ],
        ];
    }
}

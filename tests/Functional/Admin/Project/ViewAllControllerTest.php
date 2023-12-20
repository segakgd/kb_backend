<?php

namespace App\Tests\Functional\Admin\Project;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ViewAllControllerTest extends ApiTestCase
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
        $client->loginUser($user);

        $client->request(
            'GET',
            '/api/admin/project/',
            [],
            [],
            [],
            json_encode($requestContent)
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // todo когда будет готова реализация - проверить изменения в базе
    }

    private function positive(): iterable
    {
        yield [
            [
                [
                    "name" => "Название проекта",
                    "status" => "active",
                    "activeTo" => "2023-12-20T15:20:34+00:00",
                    "activeFrom" => "2023-12-20T15:20:34+00:00",
                    "statistic" => [
                        "lead" => [
                            "count" => 13
                        ],
                        "booking" => [
                            "count" => 13
                        ],
                        "chats" => [
                            "count" => 13
                        ]
                    ]
                ],
                [
                    "name" => "Название проекта",
                    "status" => "active",
                    "activeTo" => "2023-12-20T15:20:34+00:00",
                    "activeFrom" => "2023-12-20T15:20:34+00:00",
                    "statistic" => [
                        "lead" => [
                            "count" => 13
                        ],
                        "booking" => [
                            "count" => 13
                        ],
                        "chats" => [
                            "count" => 13
                        ]
                    ]
                ]
            ]
        ];
    }
}

<?php

namespace App\Tests\Functional\Admin\Project;

use App\Entity\User\Project;
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
    public function testViewAll(array $response)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);

        $this->createProject($entityManager, $user);
        $this->createProject($entityManager, $user);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            'GET',
            '/api/admin/project/',
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertResponse($client->getResponse()->getContent(), $response);
    }

    private function positive(): iterable
    {
        yield [
            [
                [
                    "name" => "Проект тестовый",
                    "status" => Project::STATUS_ACTIVE,
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
                    "name" => "Проект тестовый",
                    "status" => Project::STATUS_ACTIVE,
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

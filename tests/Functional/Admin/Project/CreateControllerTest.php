<?php

namespace App\Tests\Functional\Admin\Project;

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
    public function test(array $requestContent, array $response)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            'POST',
            '/api/admin/project/',
            [],
            [],
            [],
            json_encode($requestContent)
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertResponse($client->getResponse()->getContent(), $response);

        // todo проверить что вместе с проектом создаются и настройки
    }

    private function positive(): iterable
    {
        yield [
            [
                'name' => 'Новый проект',
                'mode' => 'shop',
                'bot' => 'vk',
            ],
            [
                "name" => "Новый проект",
                "status" => "active",
                "activeTo" => null,
                "activeFrom" => null,
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
        ];
    }
}

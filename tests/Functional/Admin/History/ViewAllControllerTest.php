<?php

namespace App\Tests\Functional\Admin\History;

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
    public function testViewAll(array $requestContent, array $response)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            'GET',
            '/api/admin/project/'. $project->getId() .'/history/',
            [],
            [],
            [],
            json_encode($requestContent)
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $responseArr = json_decode($client->getResponse()->getContent(), true);
        $this->assertResponse($responseArr, $response);
    }


    private function positive(): iterable
    {
        yield [
            [
                'filter' => 'status',
            ],
            [
                [
                    "createdAt" => "2023-12-16T23:22:00+00:00",
                    "type" => "sendingMessage",
                    "status" => "error",
                    "sender" => "vk",
                    "recipient" => "@user_name",
                    "error" => [
                        "code" => "FAKE_CODE",
                        "context" => [
                            [
                            ]
                        ]
                    ]
                ],
                [
                    "createdAt" => "2023-12-16T23:22:00+00:00",
                    "type" => "sendingMessage",
                    "status" => "error",
                    "sender" => "vk",
                    "recipient" => "@user_name",
                    "error" => [
                        "code" => "FAKE_CODE",
                        "context" => [
                            [
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}

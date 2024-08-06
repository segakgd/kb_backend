<?php

namespace App\Tests\Functional\Admin\Project;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
class ViewAllControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;

    /**
     * @dataProvider positive
     *
     * @throws Exception
     */
    public function test(array $response)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);

        $this->createProject($entityManager, $user);
        $this->createProject($entityManager, $user);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            method: 'GET',
            uri: '/api/admin/project/',
        );

        $this->assertEquals(
            expected: Response::HTTP_OK,
            actual: $client->getResponse()->getStatusCode(),
            message: $client->getResponse()->getContent()
        );

        $responseArr = json_decode($client->getResponse()->getContent(), true);
        $this->assertResponse($responseArr, $response);
    }

    private function positive(): iterable
    {
        yield [
            [
                [
                    'name'       => 'Проект тестовый',
                    'status'     => 'active',
                    'activeTo'   => '2023-12-29T17:35:02+00:00',
                    'activeFrom' => '2023-12-29T17:35:02+00:00',
                    'statistic'  => [
                        'lead' => [
                            'count' => 13,
                        ],
                        'booking' => [
                            'count' => 13,
                        ],
                        'chats' => [
                            'count' => 13,
                        ],
                    ],
                ],
                [
                    'name'       => 'Проект тестовый',
                    'status'     => 'active',
                    'activeTo'   => '2023-12-29T17:35:02+00:00',
                    'activeFrom' => '2023-12-29T17:35:02+00:00',
                    'statistic'  => [
                        'lead' => [
                            'count' => 13,
                        ],
                        'booking' => [
                            'count' => 13,
                        ],
                        'chats' => [
                            'count' => 13,
                        ],
                    ],
                ],
            ],
        ];
    }
}

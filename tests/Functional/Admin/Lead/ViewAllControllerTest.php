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
            method: 'GET',
            uri: '/api/admin/project/' . $project->getId() . '/lead/',
            content: json_encode($requestContent)
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
                'script'    => 'all',
                'messenger' => 'vk',
            ],
            [
                [
                    'number'        => 111,
                    'status'        => 'new',
                    'fullName'      => 'Fake Faker Fake',
                    'totalAmount'   => 30000,
                    'totalAmountWF' => '300,00',
                    'contacts'      => [
                        'mail' => [
                            'type'  => 'mail',
                            'name'  => 'Почта',
                            'value' => 'mail@mail.fake',
                        ],
                    ],
                    'type'          => 'service',
                    'paymentStatus' => true,
                ],
                [
                    'number'        => 111,
                    'status'        => 'new',
                    'fullName'      => 'Fake Faker Fake',
                    'totalAmount'   => 30000,
                    'totalAmountWF' => '300,00',
                    'contacts'      => [
                        'mail' => [
                            'type'  => 'mail',
                            'name'  => 'Почта',
                            'value' => 'mail@mail.fake',
                        ],
                    ],
                    'type'          => 'service',
                    'paymentStatus' => true,
                ],
            ],
        ];
    }
}

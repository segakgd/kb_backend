<?php

namespace App\Tests\Functional\Admin\Promotion;

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
            'GET',
            '/api/admin/project/' . $project->getId() . '/promotion/',
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
                'name'               => 'promo',
                'type'               => 'percent',
                'code'               => '1232',
                'triggersQuantity'   => 100,
                'isActive'           => true,
                'amount'             => 15,
                'amountWithFraction' => '10,00',
                'activeFrom'         => '2023-12-16T23:22:00+00:00',
                'activeTo'           => '2023-12-25T23:22:00+00:00',
            ],
            [
                [
                    'discounts' => [
                        [
                            'name'               => 'discount',
                            'type'               => 'percent',
                            'triggersQuantity'   => 10000,
                            'active'             => true,
                            'amount'             => 1000,
                            'amountWithFraction' => '10,00',
                        ],
                    ],
                    'promoCodes' => [
                        [
                            'name'               => 'promo',
                            'type'               => 'current',
                            'triggersQuantity'   => 10000,
                            'active'             => true,
                            'amount'             => 1000,
                            'amountWithFraction' => '10,00',
                        ],
                    ],
                ],
                [
                    'discounts' => [
                        [
                            'name'               => 'discount',
                            'type'               => 'percent',
                            'triggersQuantity'   => 10000,
                            'active'             => true,
                            'amount'             => 1000,
                            'amountWithFraction' => '10,00',
                        ],
                    ],
                    'promoCodes' => [
                        [
                            'name'               => 'promo',
                            'type'               => 'current',
                            'triggersQuantity'   => 10000,
                            'active'             => true,
                            'amount'             => 1000,
                            'amountWithFraction' => '10,00',
                        ],
                    ],
                ],
            ],
        ];
    }
}

<?php

namespace App\Tests\Functional\Admin\Promotion;

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

        $client->loginUser($user);

        $client->request(
            'GET',
            '/api/admin/project/'. $project->getId() .'/promotion/',
            [],
            [],
            [],
            json_encode($requestContent)
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertResponse($client->getResponse()->getContent(), $response);
    }


    private function positive(): iterable
    {
        yield [
            [
                'name' => 'promo',
                'type' => 'percent',
                'code' => '1232',
                'triggersQuantity' => 100,
                'isActive' => true,
                'amount' => 15,
                '$amountWithFraction' => '10',
                'activeFrom' => '2023-12-16T23:22:00+00:00',
                'activeTo' => '2023-12-25T23:22:00+00:00'
            ],
            [
                [
                    "number" => 111,
                    "status" => "new",
                    "fullName" => "Fake Faker Fake",
                    "totalAmount" => 30000,
                    "totalAmountWF" => "300,00",
                    "contacts" => [
                        "mail" => [
                            "type" => "mail",
                            "name" => "Почта",
                            "value" => "mail@mail.fake"
                        ]
                    ],
                    "type" => "service",
                    "paymentStatus" => true
                ],
                [
                    "number" => 111,
                    "status" => "new",
                    "fullName" => "Fake Faker Fake",
                    "totalAmount" => 30000,
                    "totalAmountWF" => "300,00",
                    "contacts" => [
                        "mail" => [
                            "type" => "mail",
                            "name" => "Почта",
                            "value" => "mail@mail.fake"
                        ]
                    ],
                    "type" => "service",
                    "paymentStatus" => true
                ]
            ]
        ];
    }
}

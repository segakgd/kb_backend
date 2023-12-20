<?php

namespace App\Tests\Functional\Admin\Project\Setting;

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
        $project = $this->createProject($entityManager, $user);

        $client->loginUser($user);

        $client->request(
            'GET',
            '/api/admin/project/'. $project->getId() .'/setting/',
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertResponse($client->getResponse()->getContent(), $response);
    }

    private function positive(): iterable
    {
        yield [
            [
                "mainSettings" => [
                    "name" => "Мой первый проект",
                    "country" => "russia",
                    "timeZone" => "Europe/Moscow",
                    "language" => "ru",
                    "currency" => "RUB",
                    "tariff" => [
                        "name" => "Самый лучший тариф",
                        "price" => 100000,
                        "priceWF" => "1000,00",
                    ]
                ],
                "notificationSetting" => [
                    "newLead" => [
                        "mail" => true,
                        "telegram" => false,
                        "sms" => true,
                    ],
                    "changesStatusLead" => [
                        "mail" => true,
                        "sms" => true,
                    ]
                ]
            ]
        ];
    }
}

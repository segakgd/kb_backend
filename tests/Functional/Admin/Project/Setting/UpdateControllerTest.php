<?php

namespace App\Tests\Functional\Admin\Project\Setting;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class UpdateControllerTest extends ApiTestCase
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
        $project = $this->createProject($entityManager, $user);

        $client->loginUser($user);

        $client->request(
            'POST',
            '/api/admin/project/'. $project->getId() .'/setting/',
            [],
            [],
            [],
            json_encode($requestContent)
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());

        // todo когда будет готова реализация - проверить изменения в базе
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

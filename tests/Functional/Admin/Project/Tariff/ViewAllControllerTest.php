<?php

namespace App\Tests\Functional\Admin\Project\Tariff;

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
    public function test(array $response)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            'GET',
            '/api/admin/project/'. $project->getId() .'/setting/tariff/',
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertResponse($client->getResponse()->getContent(), $response);
    }

    private function positive(): iterable
    {
        yield [
            [
                [
                    "name" => "Название тарифа",
                    "code" => "CODE_2024",
                    "price" => 100000,
                    "priceWF" => "1000,00",
                    "description" => "Какое-то описание тарифа ",
                    "active" => true,
                ],
                [
                    "name" => "Название тарифа",
                    "code" => "CODE_2024",
                    "price" => 100000,
                    "priceWF" => "1000,00",
                    "description" => "Какое-то описание тарифа ",
                    "active" => true,
                ]
            ]
        ];
    }
}

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
        $this->initProject($entityManager, $user);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            'GET',
            '/api/admin/tariffs/',
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $jsonResponse = $client->getResponse()->getContent();

        $actualResponse = json_decode($jsonResponse, true);
        $actualResponseItem = $actualResponse[count($actualResponse) - 1]; // todo не самое лучшее решение, но пока что какие-то тарблы с бандлом тестовым, мб уйдёт проблема при переходе на нормальную базу

        $this->assertResponse(json_encode($actualResponseItem), $response, ['id', 'code']); // берём последний, так себе решение
    }

    private function positive(): iterable
    {
        yield [
            [
                "name" => "Тестовый тариф",
                "price" => 10000,
                "priceWF" => "100,00",
                "description" => "For test",
                "active" => true
            ]
        ];
    }
}

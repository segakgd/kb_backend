<?php

namespace App\Tests\Functional\Admin\Promotion;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ViewOneControllerTest extends ApiTestCase
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

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            'GET',
            '/api/admin/project/'. $project->getId() .'/promotion/' . 1 . '/', // todo ВНИМАНИЕ! я пока что поставил 1, но нужно брать существующую промоакцию
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertResponse($client->getResponse()->getContent(), $response);
    }


    private function positive(): iterable
    {
        yield [
            [
                "name" => "promo",
                "type" => "current",
                "code" => "2024",
                "triggersQuantity" => 10000,
                "active" => true,
                "amount" => 1000,
                "amountWithFraction" => "10,00",
                "activeFrom" => "2023-12-25T13:24:08+00:00",
                "activeTo" => "2023-12-25T13:24:08+00:00"
            ]
        ];
    }
}

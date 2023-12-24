<?php

namespace App\Tests\Functional\Admin\Shipping;

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
    public function testViewOne(array $response)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();
        $user = $this->createUser($entityManager);

        $project = $this->createProject($entityManager, $user);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            'GET',
            '/api/admin/project/'. $project->getId() .'/shipping/' . 1 . '/', // todo ВНИМАНИЕ! я пока что поставил 1, но нужно брать существующую доставку
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertResponse($client->getResponse()->getContent(), $response);
    }


    private function positive(): iterable
    {
        yield [
            [
                "name" => "shipping 1",
                "type" => "pickup",
                "calculationType" => "percent",
                "amount" => 1000,
                "applyFromAmount" => 10000,
                "applyFromAmountWF" => "100,00",
                "applyToAmount" => 10000,
                "applyToAmountWF" => "10,00",
                "description" => "Urgent delivery is required",
                "fields" => [
                [
                  "name" => "Добавочный телефон",
                  "value" => "2396",
                  "type" => "phone",
                ],
            ],
                "active" => true
            ],
        ];
    }
}

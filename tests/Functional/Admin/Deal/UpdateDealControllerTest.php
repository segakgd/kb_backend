<?php

namespace App\Tests\Functional\Admin\Deal;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Deal\DealTrait;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class UpdateDealControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;
    use DealTrait;

    /**
     * @throws Exception
     */
    public function testWithoutAuth()
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);
        $deal = $this->createDeal($entityManager, $project);

        $client->request(
            'PUT',
            '/api/admin/project/' . $project->getId() .'/deal/'. $deal->getId() . '/',
        );

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider positive
     *
     * @throws Exception
     */
    public function testUpdate($requestContent)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);
        $deal = $this->createDeal($entityManager, $project);

        $client->loginUser($user);

        $client->request(
            'PUT',
            '/api/admin/project/' . $project->getId() .'/deal/'. $deal->getId() . '/',
            [],
            [],
            [],
            json_encode($requestContent)
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $deal = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($deal['contacts']['firstName'] === 'asdGHbdtm');
        $this->assertTrue($deal['contacts']['lastName'] === 'sdsd');
        $this->assertTrue($deal['contacts']['phone'] === 'GHbdtm');
        $this->assertTrue($deal['contacts']['email'] === 'GHbdtm');

        $this->assertTrue(isset($deal['order']));

        $this->assertTrue($deal['fields'][0]['name'] === 'sadasd');
        $this->assertTrue($deal['fields'][0]['value'] === 'yttyt');
    }

    private function positive(): iterable
    {
        yield [
            'requestContent' => [
                "contacts" => [
                    "firstName" => "asdGHbdtm",
                    "phone" => "GHbdtm",
                    "email" => "GHbdtm",
                    "lastName" => "sdsd"
                ],
                "fields" => [
                    [
                        "name" => "sadasd",
                        "value" => "yttyt"
                    ],
                ],
                "order" => [
                    "products" => [],
                    "shipping" => [],
                    "promotions" => [],
                    "totalAmount" => 20000,
                ],
            ],
        ];
    }
}

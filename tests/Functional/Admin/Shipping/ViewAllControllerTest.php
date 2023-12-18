<?php

namespace App\Tests\Functional\Admin\Shipping;

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
            '/api/admin/project/'. $project->getId() .'/shipping/',
            [],
            [],
            [],
            json_encode($requestContent)
        );
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertResponse($client->getResponse()->getContent(), $response);
    }


    private function positive(): iterable  // todo разобраться с тестом ...
    {
        yield [
            [
            [
                'name' => 'shipping 1',
                'type' => 'pickup',
                'applyFromAmount' => 100,
                'applyFromAmountWF' => '100',
                'applyToAmount' => 10,
                'applyToAmountWF' => '10',
                'active' => true,
            ],
                [
                'name' => 'shipping 1',
                'type' => 'pickup',
                'applyFromAmount' => 100,
                'applyFromAmountWF' => '100',
                'applyToAmount' => 10,
                'applyToAmountWF' => '10',
                'active' => true,
            ],
            ],
        ];
    }
}

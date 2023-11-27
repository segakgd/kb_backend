<?php

namespace App\Tests\Functional\Admin\Deal;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Deal\DealTrait;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class GetDealControllerTest extends ApiTestCase
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
            'GET',
            '/api/admin/project/' . $project->getId() .'/deal/'. $deal->getId() . '/',
        );

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    /**
     * @throws Exception
     */
    public function testGetOneDeal()
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);
        $deal = $this->createDeal($entityManager, $project);

        $client->loginUser($user);

        $client->request(
            'GET',
            '/api/admin/project/' . $project->getId() .'/deal/'. $deal->getId() . '/',
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $dealResponse = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($dealResponse['id'] === $deal->getId());
    }
}

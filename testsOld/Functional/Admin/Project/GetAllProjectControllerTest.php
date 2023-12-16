<?php

namespace App\Tests\Functional\Admin\Project;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class GetAllProjectControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;

    /**
     * @throws Exception
     */
    public function testWithoutAuth()
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/api/admin/projects/',
        );

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    /**
     * @throws Exception
     */
    public function testGetAll()
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project1 = $this->createProject($entityManager, $user);
        $project2 = $this->createProject($entityManager, $user);

        $client->loginUser($user);

        $client->request(
            'GET',
            '/api/admin/projects/',
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $projects = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue(count($projects) === 2);

        $this->assertTrue($projects[0]['id'] === $project1->getId());
        $this->assertTrue($projects[1]['id'] === $project2->getId());
    }
}

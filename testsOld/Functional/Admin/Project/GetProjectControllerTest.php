<?php

namespace App\Tests\Functional\Admin\Project;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class GetProjectControllerTest extends ApiTestCase
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
            '/api/admin/projects/1/',
        );

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    /**
     * @throws Exception
     */
    public function testGetOne()
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $projectEntity = $this->createProject($entityManager, $user);

        $client->loginUser($user);

        $client->request(
            'GET',
            '/api/admin/projects/' . $projectEntity->getId() . '/',
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $project = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($project['id'] === $projectEntity->getId());
    }
}

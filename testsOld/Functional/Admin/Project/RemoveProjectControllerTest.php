<?php

namespace App\Tests\Functional\Admin\Project;

use App\Entity\User\Project;
use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class RemoveProjectControllerTest extends ApiTestCase
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
            'DELETE',
            '/api/admin/projects/1/',
        );

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }


    /**
     * @throws Exception
     */
    public function testRemove(){
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);

        $projectId = $project->getId();

        $projectEntity = $entityManager->getRepository(Project::class)->find($projectId);
        $this->assertTrue($projectEntity instanceof Project);

        $client->loginUser($user);
        $client->request(
            'DELETE',
            '/api/admin/projects/' . $projectId . '/',
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());

        $projectEntity = $entityManager->getRepository(Project::class)->find($projectId);
        $this->assertFalse($projectEntity instanceof Project);
    }
}

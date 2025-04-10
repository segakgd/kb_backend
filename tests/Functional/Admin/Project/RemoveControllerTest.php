<?php

namespace App\Tests\Functional\Admin\Project;

use App\Entity\User\Project;
use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
class RemoveControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;

    /**
     * @throws Exception
     */
    public function test()
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);

        $entityManager->flush();

        $client->loginUser($user);

        $projectId = $project->getId();

        $client->request(
            method: 'DELETE',
            uri: '/api/admin/project/' . $project->getId() . '/',
        );

        $this->assertEquals(
            expected: Response::HTTP_NO_CONTENT,
            actual: $client->getResponse()->getStatusCode(),
            message: $client->getResponse()->getContent()
        );

        $projectRepository = $entityManager->getRepository(Project::class);

        $this->assertNull($projectRepository->find($projectId));
    }
}

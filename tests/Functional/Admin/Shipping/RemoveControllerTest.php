<?php

namespace App\Tests\Functional\Admin\Shipping;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class RemoveControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;

    /**
     * @throws Exception
     */
    public function testOneDelete()
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            'DELETE',
            '/api/admin/project/'. $project->getId() .'/shipping/' . 1 . '/',
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());
    }
}

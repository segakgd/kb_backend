<?php

namespace App\Tests\Functional\Admin\Project;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class UpdateProjectControllerTest extends ApiTestCase
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
            'PUT',
            '/api/admin/projects/1/',
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

        $client->loginUser($user);

        $client->request(
            'PUT',
            '/api/admin/projects/' . $project->getId() .'/',
            [],
            [],
            [],
            json_encode($requestContent)
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $deal = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($deal['name'] === 'new name');
    }

    private function positive(): iterable
    {
        yield [
            'requestContent' => [
                "name" => 'new name',
            ],
        ];
    }
}

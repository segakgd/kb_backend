<?php

namespace App\Tests\Functional\Admin\Project;

use App\Entity\User\Project;
use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class CreateProjectControllerTest extends ApiTestCase
{
    use UserTrait;

    /**
     * @throws Exception
     */
    public function testWithoutAuth()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/admin/projects/',
        );

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider positiveVariants
     *
     * @throws Exception
     */
    public function testCreate(array $requestContent)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);

        $client->loginUser($user);

        $client->request(
            'POST',
            '/api/admin/projects/',
            [],
            [],
            [],
            json_encode($requestContent)
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $project = json_decode($client->getResponse()->getContent(), true);

        $contactEntity = $entityManager->getRepository(Project::class)->find($project['id']);
        $this->assertEquals($contactEntity->getId(), $project['id']);
        $this->assertEquals($contactEntity->getName(), $project['name']);
    }

    private function positiveVariants(): iterable
    {
        yield [
            'requestContent' => [
                "name" => 'project name '. uniqid(),
            ],
        ];
    }
}

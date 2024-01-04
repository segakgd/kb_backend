<?php

namespace App\Tests\Functional\Admin\Bot;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Bot\BotTrait;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ViewAllControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;
    use BotTrait;

    /**
     * @dataProvider positive
     *
     * @throws Exception
     */
    public function testViewAll(array $response)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);

        $entityManager->flush();

        $this->createBot($entityManager, $project);
        $this->createBot($entityManager, $project);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            'GET',
            '/api/admin/project/'. $project->getId() .'/bot/',
        );

        $responseArr = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertResponse($responseArr, $response);
    }

    private function positive(): iterable
    {
        yield [
            [
                [
                    "name" => "Мой новый бот",
                    "type" => "telegram"
                ],
                [
                    "name" => "Мой новый бот",
                    "type" => "telegram"
                ],
            ]
        ];
    }
}

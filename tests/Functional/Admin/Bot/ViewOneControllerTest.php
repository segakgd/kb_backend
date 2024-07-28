<?php

namespace App\Tests\Functional\Admin\Bot;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Bot\BotTrait;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
class ViewOneControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;
    use BotTrait;

    /**
     * @dataProvider positive
     *
     * @throws Exception
     */
    public function test(array $response)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);

        $entityManager->flush();

        $bot = $this->createBot($entityManager, $project);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            'GET',
            '/api/admin/project/' . $project->getId() . '/bot/' . $bot->getId() . '/' // todo ВНИМАНИЕ! я пока что поставил 1, но нужно брать существующий продукт
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $responseArr = json_decode($client->getResponse()->getContent(), true);
        $this->assertResponse($responseArr, $response);
    }

    private function positive(): iterable
    {
        yield [
            [
                'name' => 'Мой новый бот',
                'type' => 'telegram',
            ],
        ];
    }
}

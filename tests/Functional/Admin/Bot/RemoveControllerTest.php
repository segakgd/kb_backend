<?php

namespace App\Tests\Functional\Admin\Bot;

use App\Entity\User\Bot;
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
class RemoveControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;
    use BotTrait;

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

        $bot = $this->createBot($entityManager, $project);

        $entityManager->flush();

        $botId = $bot->getId();
        $client->loginUser($user);

        $client->request(
            'DELETE',
            '/api/admin/project/' . $project->getId() . '/bot/' . $botId . '/',
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());

        $botRepository = $entityManager->getRepository(Bot::class);

        $bot = $botRepository->find($botId);

        $this->assertNull($bot);
    }
}

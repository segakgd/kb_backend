<?php

namespace App\Tests\Functional\Admin\Bot;

use App\Entity\User\Bot;
use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Bot\BotTrait;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @internal
 * @coversNothing
 */
class CreateControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;
    use BotTrait;

    private SerializerInterface $serializer;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->serializer = $this->getContainer()->get('serializer');
    }

    public function tearDown(): void
    {
        parent::tearDown();

        unset($this->serializer);
    }

    /**
     * @dataProvider positive
     *
     * @throws Exception
     */
    public function test(array $requestContent)
    {
        self::ensureKernelShutdown();

        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            'POST',
            '/api/admin/project/' . $project->getId() . '/bot/',
            [],
            [],
            [],
            json_encode($requestContent)
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $response = $client->getResponse()->getContent();
        $response = json_decode($response, true);

        $botId = $response['id'];

        $botRepository = $entityManager->getRepository(Bot::class);

        $bot = $botRepository->find($botId);

        $this->assertNotNull($bot);

        $normalBot = $this->serializer->normalize($bot);

        $this->assertResponse($response, $normalBot);
    }

    private function positive(): iterable
    {
        yield [
            [
                'name'  => 'Тестовый бот',
                'type'  => 'telegram',
                'token' => '0000000000:0000000000-0000000000',
            ],
        ];
    }
}

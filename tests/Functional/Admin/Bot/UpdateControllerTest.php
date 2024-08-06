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
class UpdateControllerTest extends ApiTestCase
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
    public function test(array $requestContent, array $response)
    {
        self::ensureKernelShutdown();

        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);

        $entityManager->flush();

        $bot = $this->createBot($entityManager, $project);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            'PATCH',
            '/api/admin/project/' . $project->getId() . '/bot/' . $bot->getId() . '/',
            [],
            [],
            [],
            json_encode($requestContent)
        );

        $this->assertEquals(
            expected: Response::HTTP_OK,
            actual: $client->getResponse()->getStatusCode(),
            message: $client->getResponse()->getContent()
        );

        $responseArr = json_decode($client->getResponse()->getContent(), true);
        $this->assertResponse($responseArr, $response);

        $botRepository = $entityManager->getRepository(Bot::class);
        $bot = $botRepository->find($responseArr['id']);

        $this->assertNotNull($bot);

        $normalBot = $this->serializer->normalize($bot);

        $this->assertResponse($response, $normalBot);
    }

    private function positive(): iterable
    {
        yield [
            [
                'name'  => 'Новое название бота',
                'type'  => 'vk',
                'token' => '0000000000:0000000000-1111111111',
            ],
            [
                'name' => 'Новое название бота',
                'type' => 'vk',
            ],
        ];
    }
}

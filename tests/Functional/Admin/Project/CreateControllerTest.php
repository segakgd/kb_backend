<?php

namespace App\Tests\Functional\Admin\Project;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class CreateControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;

    /**
     * @dataProvider positive
     *
     * @throws Exception
     */
    public function testViewAll(array $requestContent)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            'POST',
            '/api/admin/project/',
            [],
            [],
            [],
            json_encode($requestContent)
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());

        // todo когда будет готова реализация - проверить изменения в базе
    }

    private function positive(): iterable
    {
        yield [
            [
                'name' => 'Новый проект',
                'mode' => 'shop',
                'bot' => 'vk',
            ]
        ];
    }
}

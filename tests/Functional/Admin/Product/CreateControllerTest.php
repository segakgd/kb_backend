<?php

namespace App\Tests\Functional\Admin\Product;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
class CreateControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;

    /**
     * @dataProvider positive
     *
     * @throws Exception
     */
    public function test(array $requestContent)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            method: 'POST',
            uri: '/api/admin/project/' . $project->getId() . '/product/',
            content: json_encode($requestContent)
        );

        $this->assertEquals(
            expected: Response::HTTP_NO_CONTENT,
            actual: $client->getResponse()->getStatusCode(),
            message: $client->getResponse()->getContent()
        );

        // todo когда будет готова реализация - проверить изменения в базе
    }

    private function positive(): iterable
    {
        yield [
            [
                'name'        => 'Подушка',
                'article'     => 'ARTICLE_2024',
                'type'        => 'service',
                'visible'     => true,
                'description' => 'Какое-то описание',
                'image'       => 'image.fake',
                'category'    => [
                    [
                        'id'   => 111,
                        'name' => 'Category name',
                    ],
                ],
                'variants' => [
                    [
                        'name'  => 'Красная',
                        'count' => 1,
                        'price' => 10000,
                    ],
                    [
                        'name'  => 'Синяя',
                        'count' => 1,
                        'price' => 10000,
                    ],
                ],
            ],
        ];
    }
}

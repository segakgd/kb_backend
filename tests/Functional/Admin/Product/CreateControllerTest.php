<?php

namespace App\Tests\Functional\Admin\Product;

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
        $project = $this->createProject($entityManager, $user);

        $client->loginUser($user);

        $client->request(
            'POST',
            '/api/admin/project/'. $project->getId() .'/product/',
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
                'name' => 'Подушка',
                'article' => 'ARTICLE_2024',
                'type' => 'service',
                'visible' => true,
                'description' => 'Какое-то описание',
                'image' => 'image.fake',
                'category' => [
                    [
                        'id' => 111,
                        'name' => 'Category name',
                    ]
                ],
                'variants' => [
                    [
                        'name' => 'Красная',
                        'count' => 1,
                        'price' => 10000,
                    ],
                    [
                        'name' => 'Синяя',
                        'count' => 1,
                        'price' => 10000,
                    ],
                ],
            ],
        ];
    }
}

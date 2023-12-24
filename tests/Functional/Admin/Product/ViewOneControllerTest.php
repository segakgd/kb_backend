<?php

namespace App\Tests\Functional\Admin\Product;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ViewOneControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;

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

        $client->loginUser($user);

        $client->request(
            'GET',
            '/api/admin/project/'. $project->getId() .'/product/' . 1 . '/', // todo ВНИМАНИЕ! захардкодил 1
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertResponse($client->getResponse()->getContent(), $response);
    }


    private function positive(): iterable
    {
        yield [
            [
                "id" => 111,
                "name" => "Продукт",
                "article" => "ARTICLE",
                "type" => "product",
                "visible" => true,
                "description" => "Какое-то описание чего-либо",
                "image" => "image.fake",
                "category" => [
                    [
                        "id" => 111,
                        "name" => "Имя категории"
                    ]
                ],
                "variants" => [
                    [
                        "name" => "Имя варианта",
                        "count" => 1,
                        "price" => 10000
                    ]
                ]
            ]
        ];
    }
}

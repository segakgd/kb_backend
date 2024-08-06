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
class ViewAllControllerTest extends ApiTestCase
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
            '/api/admin/project/' . $project->getId() . '/product/',
        );

        $this->assertEquals(
            expected: Response::HTTP_OK,
            actual: $client->getResponse()->getStatusCode(),
            message: $client->getResponse()->getContent()
        );

        $responseArr = json_decode($client->getResponse()->getContent(), true);
        $this->assertResponse($responseArr, $response);
    }

    private function positive(): iterable
    {
        yield [
            [
                [
                    'id'               => 111,
                    'name'             => 'Продукт',
                    'article'          => 'PRODUCT-1',
                    'visible'          => true,
                    'type'             => 'service',
                    'affordablePrices' => '100-200',
                    'count'            => 1,
                ],
                [
                    'id'               => 111,
                    'name'             => 'Продукт',
                    'article'          => 'PRODUCT-1',
                    'visible'          => true,
                    'type'             => 'service',
                    'affordablePrices' => '100-200',
                    'count'            => 1,
                ],
            ],
        ];
    }
}

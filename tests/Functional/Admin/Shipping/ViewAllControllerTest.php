<?php

namespace App\Tests\Functional\Admin\Shipping;

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
    public function testViewAll(array $requestContent, array $response)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);

        $entityManager->flush();

        $client->loginUser($user);
        $client->request(
            method: 'GET',
            uri: '/api/admin/project/' . $project->getId() . '/shipping/',
            content: json_encode($requestContent)
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
                'type'  => 'phone',
                'name'  => 'Добавочный телефон',
                'value' => '2396',
            ],
            [
                [
                    'name'              => 'Доставка до самого дома',
                    'applyFromAmount'   => 10000,
                    'applyFromAmountWF' => '100,00',
                    'applyToAmount'     => 1000,
                    'applyToAmountWF'   => '10,00',
                    'active'            => true,
                    'type'              => 'pickup',
                ],
                [
                    'name'              => 'Доставка до самого дома',
                    'applyFromAmount'   => 10000,
                    'applyFromAmountWF' => '100,00',
                    'applyToAmount'     => 1000,
                    'applyToAmountWF'   => '10,00',
                    'active'            => true,
                    'type'              => 'pickup',
                ],
            ],
        ];
    }
}

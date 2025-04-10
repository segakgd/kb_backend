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
class ViewOneControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;

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

        $client->loginUser($user);

        $client->request(
            method: 'GET',
            uri: '/api/admin/project/' . $project->getId() . '/shipping/' . 1 . '/', // todo ВНИМАНИЕ! я пока что поставил 1, но нужно брать существующую доставку
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
                'name'              => 'Доставка до самого дома',
                'type'              => 'pickup',
                'calculationType'   => 'percent',
                'amount'            => 1000,
                'applyFromAmount'   => 10000,
                'applyFromAmountWF' => '100,00',
                'applyToAmount'     => 10000,
                'applyToAmountWF'   => '10,00',
                'description'       => 'Urgent delivery is required',
                'fields'            => [
                    [
                        'name'  => 'Добавочный телефон',
                        'value' => '2396',
                        'type'  => 'phone',
                    ],
                ],
                'active' => true,
            ],
        ];
    }
}

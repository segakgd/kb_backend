<?php

namespace App\Tests\Functional\Admin\Promotion;

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
            uri: '/api/admin/project/' . $project->getId() . '/promotion/' . 1 . '/', // todo ВНИМАНИЕ! я пока что поставил 1, но нужно брать существующую промоакцию
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
                'name'               => 'promo',
                'type'               => 'current',
                'code'               => '2024',
                'triggersQuantity'   => 10000,
                'active'             => true,
                'amount'             => 1000,
                'amountWithFraction' => '10,00',
                'activeFrom'         => '2023-12-25T13:24:08+00:00',
                'activeTo'           => '2023-12-25T13:24:08+00:00',
            ],
        ];
    }
}

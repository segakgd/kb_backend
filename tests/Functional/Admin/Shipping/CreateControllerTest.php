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
class CreateControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;

    /**
     * @dataProvider positive
     *
     * @throws Exception
     */
    public function testCreate(array $requestContent)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            method: 'POST',
            uri: '/api/admin/project/' . $project->getId() . '/shipping/',
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
                'shipping' => [
                    'name'              => 'Доставка до самого дома',
                    'type'              => 'pickup',
                    'calculationType'   => 'percent',
                    'amount'            => 10,
                    'amountWF'          => '100,00',
                    'applyFromAmount'   => 20000,
                    'applyFromAmountWF' => '100,00',
                    'applyToAmount'     => 10000,
                    'applyToAmountWF'   => '100,00',
                    'description'       => 'Urgent delivery is required',
                    'fields'            => [
                        'type'  => 'phone',
                        'name'  => 'Добавочный телефон',
                        'value' => '2396',
                    ],
                    'isActive' => true,
                ],
            ],
        ];
    }
}

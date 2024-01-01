<?php

namespace App\Tests\Functional\Admin\Shipping;

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
    public function testCreate(array $requestContent)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            'POST',
            '/api/admin/project/'. $project->getId() .'/shipping/',
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
                "shipping" => [
                    'name' => 'Доставка до самого дома',
                    'type' => 'pickup',
                    'calculationType' => 'percent',
                    'amount' => 10,
                    'amountWF' => '100,00',
                    'applyFromAmount' => 20000,
                    'applyFromAmountWF' => '100,00',
                    'applyToAmount' => 10000,
                    'applyToAmountWF' => '100,00',
                    'description' => 'Urgent delivery is required',
                    'fields' => [
                        'type' => 'phone',
                        'name' => 'Добавочный телефон',
                        'value' => '2396',
                    ],
                    'isActive' => true,
                ],
            ],
        ];
    }
}

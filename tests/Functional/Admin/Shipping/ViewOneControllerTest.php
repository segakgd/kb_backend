<?php

namespace App\Tests\Functional\Admin\Shipping;

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

        $client->loginUser($user);

        $client->request(
            'GET',
            '/api/admin/project/'. $project->getId() .'/shipping/' . 1 . '/', // todo ВНИМАНИЕ! я пока что поставил 1, но нужно брать существующий продукт
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertResponse($client->getResponse()->getContent(), $response);
    }


    private function positive(): iterable
    {
        yield [
            [
                "shipping" => [
                    'name' => 'shipping 1',
                    'type' => 'pickup',
                    'calculationType' => 'percent',
                    'amount' => 10,
                    'amountWF' => '100',
                    'applyFromAmount' => 20,
                    'applyFromAmountWF' => '100',
                    'applyToAmount' => 100,
                    'applyToAmountWF' => '100',
                    'description' => 'test testtesttes ttest test test',
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

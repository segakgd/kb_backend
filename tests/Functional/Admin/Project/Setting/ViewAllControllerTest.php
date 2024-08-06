<?php

namespace App\Tests\Functional\Admin\Project\Setting;

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
    public function test(array $response)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->initProject($entityManager, $user);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            method: 'GET',
            uri: '/api/admin/project/' . $project->getId() . '/setting/',
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
                'mainSettings' => [
                    'country'  => 'russia',
                    'timeZone' => 'Europe/Moscow',
                    'language' => 'ru',
                    'currency' => 'RUB',
                    'tariff'   => [
                        'name'    => 'Триал',
                        'price'   => 0,
                        'priceWF' => '0',
                    ],
                ],
                'notificationSetting' => [
                    'newLead' => [
                        'system'   => true,
                        'mail'     => false,
                        'telegram' => false,
                        'sms'      => false,
                    ],
                    'changesStatusLead' => [
                        'system'   => true,
                        'mail'     => false,
                        'telegram' => false,
                        'sms'      => false,
                    ],
                ],
            ],
        ];
    }
}

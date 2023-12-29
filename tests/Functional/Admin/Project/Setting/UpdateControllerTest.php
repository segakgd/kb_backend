<?php

namespace App\Tests\Functional\Admin\Project\Setting;

use App\Entity\User\ProjectSetting;
use App\Entity\User\Tariff;
use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class UpdateControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;

    /**
     * @dataProvider positive
     *
     * @throws Exception
     */
    public function test(array $requestContent, array $correctResponse)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->initProject($entityManager, $user);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            'POST',
            '/api/admin/project/'. $project->getId() .'/setting/',
            [],
            [],
            [],
            json_encode($requestContent)
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $projectSettingRepository = $entityManager->getRepository(ProjectSetting::class);
        $tariffRepository = $entityManager->getRepository(Tariff::class);

        $responseContent = json_decode($client->getResponse()->getContent(), true);

        $projectSetting = $projectSettingRepository->find($responseContent['id'] ?? null);

        $serializer = $this->getContainer()->get('serializer');

        $projectSetting = $serializer->normalize($projectSetting);

        $tariffRepository = $tariffRepository->find($projectSetting['tariffId'] ?? null);

        $correctResponse['projectId'] = $project->getId();
        $correctResponse['tariffId'] = $tariffRepository->getId();

        $this->assertResponse(json_encode($projectSetting, true), $correctResponse);
    }

    private function positive(): iterable
    {
        yield [
            [
                "mainSettings" => [
                    "country" => "russia",
                    "timeZone" => "Europe/Moscow",
                    "language" => "ru",
                    "currency" => "RUB",
                ],
                "notificationSetting" => [
                    "newLead" => [
                        "mail" => true,
                        "telegram" => false,
                        "sms" => true,
                    ],
                    "changesStatusLead" => [
                        "mail" => true,
                        "sms" => true,
                    ]
                ]
            ],
            [
                "notification" =>  [
                    "aboutNewLead" =>  [
                        "system" => true,
                        "mail" => false,
                        "sms" => false,
                        "telegram" => false,
                    ],
                    "aboutChangesStatusLead" => [
                        "system" => true,
                        "mail" => false,
                        "sms" => false,
                        "telegram" => false,
                    ],
                ],
                "basic" => [
                    "country" => "russia",
                    "language" => "ru",
                    "timeZone" => "Europe/Moscow",
                    "currency" => "RUB",
                ],
            ]
        ];
    }
}

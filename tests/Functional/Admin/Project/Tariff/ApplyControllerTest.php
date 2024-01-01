<?php

namespace App\Tests\Functional\Admin\Project\Tariff;

use App\Entity\User\ProjectSetting;
use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ApplyControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;

    /**
     * @throws Exception
     */
    public function test()
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->initProject($entityManager, $user);

        $tariffForTest = $this->createTestTariff($entityManager);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            'POST',
            '/api/admin/project/'. $project->getId() .'/setting/tariff/',
            [],
            [],
            [],
            json_encode(
                [
                    'code' => $tariffForTest->getCode()
                ]
            )
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());

        $projectSettingRepository = $entityManager->getRepository(ProjectSetting::class);
        $projectSetting = $projectSettingRepository->findOneBy(
            [
                'projectId' => $project->getId()
            ]
        );

        $this->assertEquals($projectSetting->getTariffId(), $tariffForTest->getId());
    }
}

<?php

namespace App\Tests\Functional\Admin\Project\Tariff;

use App\Entity\User\ProjectSetting;
use App\Service\Common\Project\Enum\TariffCodeEnum;
use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
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
            method: 'POST',
            uri: '/api/admin/project/' . $project->getId() . '/setting/tariff/',
            content: json_encode(
                [
                    'code' => TariffCodeEnum::Trial->value,
                ]
            )
        );

        $this->assertEquals(
            expected: Response::HTTP_NO_CONTENT,
            actual: $client->getResponse()->getStatusCode(),
            message: $client->getResponse()->getContent()
        );

        $projectSettingRepository = $entityManager->getRepository(ProjectSetting::class);
        $projectSetting = $projectSettingRepository->findOneBy(
            [
                'projectId' => $project->getId(),
            ]
        );

        $this->assertEquals($projectSetting->getTariffId(), $tariffForTest->getId());
    }
}

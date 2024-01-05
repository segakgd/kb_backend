<?php

namespace App\Converter;

use App\Service\Visitor\Scenario\ScenarioServiceInterface;
use Exception;

class SettingConverter
{
    public function __construct(
        private readonly ScenarioServiceInterface $scenarioService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function convert(array $settings, int $projectId, int $ownerId = null): array
    {
        $result = [];

        foreach ($settings as $settingItem) {
            $scenario = $this->scenarioService->createScenario($settingItem, $settingItem['name'], $projectId, 'group' . $projectId, $ownerId); // todo название группы не очень

            if (isset($settingItem['sub'])){
                $resultSud = $this->convert($settingItem['sub'], $projectId, $scenario->getId());

                $result = array_merge($result, $resultSud);
            }
        }

        return $result;
    }
}

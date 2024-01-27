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
    public function convert(array $settings, int $projectId, int $botId, int $ownerId = null): array
    {
        // todo при конвертации лучше использовать транзакции
        $this->scenarioService->markAsRemoveScenario($projectId, $botId);

        $this->createDefault($projectId, $botId);

        return $this->convertItems($settings, $projectId, $botId, $ownerId );
    }

    public function convertItems(array $settings, int $projectId, int $botId, int $ownerId = null): array
    {
        $result = [];

        foreach ($settings as $settingItem) {
            $scenario = $this->scenarioService->createScenario(
                $settingItem,
                $projectId,
                'group' . $projectId,  // todo название группы не очень... а вообще нужны группы?
                $botId,
                $ownerId,
            );

            if (isset($settingItem['sub'])){
                $resultSud = $this->convertItems($settingItem['sub'], $projectId, $botId, $scenario->getId());

                $result = array_merge($result, $resultSud);
            }
        }

        return $result;
    }

    private function createDefault(int $projectId, int $botId): void
    {
        $this->scenarioService->createScenario(
            [
                "name" => "default",
                "type" => "message",
                "content" => [
                    "message"=>"Не знаю что вам ответить",
                ],
            ],
            $projectId,
            'group' . $projectId,  // todo название группы не очень... а вообще нужны группы?
            $botId,
        );
    }
}

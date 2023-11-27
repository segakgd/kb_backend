<?php

namespace App\Converter;

use App\Entity\Scenario\Scenario;
use App\Repository\Scenario\ScenarioRepository;

class SettingConverter
{
    public function __construct(
        private ScenarioRepository $behaviorScenarioRepository, // todo использовать сервис
    ) {
    }

    public function convert(array $settings, int $ownerId = null): array
    {
        $result = [];

        foreach ($settings as $key => $settingItem) {

            $step = (new Scenario())
                ->setType($settingItem['type'])
                ->setName($key)
                ->setContent($settingItem['content'])
                ->setActionAfter($settingItem['actionAfter'] ?? null)
            ;

            if ($ownerId){
                $step->setOwnerStepId($ownerId);
            }

            $this->behaviorScenarioRepository->save($step);

            if (isset($settingItem['sub'])){
                $resultSud = $this->convert($settingItem['sub'], $step->getId());

                $result = array_merge($result, $resultSud);
            }
        }

        return $result;
    }
}

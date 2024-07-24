<?php

namespace App\Service\Visitor\Scenario;

use App\Dto\Scenario\ScenarioContractDto;
use App\Dto\Scenario\ScenarioKeyboardDto;
use App\Entity\Scenario\Scenario;
use App\Enum\NavigateEnum;
use App\Enum\TargetAliasEnum;
use App\Enum\TargetEnum;
use App\Repository\Scenario\ScenarioRepository;
use App\Service\Constructor\Core\Helper\JumpHelper;
use Exception;

readonly class ScenarioService
{
    public function __construct(
        private ScenarioRepository $scenarioRepository,
    ) {}

    /**
     * @throws Exception
     */
    public function findScenarioByUUID(string $uuid): Scenario
    {
        $scenario = $this->scenarioRepository->findOneBy(
            [
                'UUID'      => $uuid,
                'deletedAt' => null,
            ]
        );

        if (null === $scenario) {
            $scenario = $this->getByAlias(TargetAliasEnum::Default);
        }

        if (null === $scenario) {
            throw new Exception('Нет сценария по умолчанию');
        }

        return $scenario;
    }

    public function getByAlias(TargetAliasEnum $alias): ?Scenario
    {
        return $this->scenarioRepository->findOneBy(
            [
                'alias'     => $alias->value,
                'deletedAt' => null,
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function findScenarioByNameAndType(string $type, string $content): Scenario
    {
        $scenario = $this->getScenarioByNameAndType($type, $content);

        $targetEnum = JumpHelper::getJumpFromNavigate($content);

        if (is_null($scenario) && !is_null($targetEnum)) {
            $scenario = $this->findScenarioByTarget($targetEnum);
        }

        if (is_null($scenario)) {
            $scenario = $this->getByAlias(TargetAliasEnum::Default);
        }

        if (is_null($scenario)) {
            throw new Exception('Нет сценария по умолчанию');
        }

        return $scenario;
    }

    public function findScenarioByTarget(TargetEnum $jumpValue): ?Scenario
    {
        return match ($jumpValue) {
            TargetEnum::Main         => $this->getByAlias(TargetAliasEnum::Main),
            TargetEnum::Cart         => $this->getByAlias(TargetAliasEnum::Cart),
            TargetEnum::PlaceAnOrder => $this->getByAlias(TargetAliasEnum::PlaceAnOrder),
            default                  => null,
        };
    }

    public function getScenarioByNameAndType(string $type, string $name): ?Scenario
    {
        return $this->scenarioRepository->findOneBy(
            [
                'type'      => $type,
                'name'      => $name,
                'deletedAt' => null,
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function generateDefaultScenario(int $projectId, int $botId): Scenario
    {
        $scenarioContractDto = (new ScenarioContractDto())
            ->setMessage('Не знаю что вам ответить')
            ->setKeyboard(
                (new ScenarioKeyboardDto())
                    ->setReplyMarkup(
                        [
                            [
                                [
                                    'text'   => NavigateEnum::ToMain,
                                    'target' => null,
                                ],
                            ],
                        ]
                    )
            );

        $scenarioEntity = (new Scenario())
            ->setUUID(uuid_create())
            ->setType('message')
            ->setAlias('default')
            ->setName('default')
            ->setProjectId($projectId)
            ->setBotId($botId)
            ->setContract($scenarioContractDto);

        $this->scenarioRepository->saveAndFlush($scenarioEntity);

        return $scenarioEntity;
    }

    public function markAllAsRemoveScenario(int $projectId, int $botId): void
    {
        $scenarios = $this->scenarioRepository->findBy(
            [
                'projectId' => $projectId,
                'botId'     => $botId,
            ]
        );

        foreach ($scenarios as $scenario) {
            $scenario->markAtDeleted();

            $this->scenarioRepository->saveAndFlush($scenario);
        }
    }
}

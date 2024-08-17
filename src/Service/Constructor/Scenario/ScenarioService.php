<?php

namespace App\Service\Constructor\Scenario;

use App\Dto\Scenario\ScenarioContractDto;
use App\Dto\Scenario\ScenarioKeyboardDto;
use App\Entity\Scenario\Scenario;
use App\Entity\User\Bot;
use App\Enum\NavigateEnum;
use App\Enum\TargetAliasEnum;
use App\Repository\Scenario\ScenarioRepository;
use Exception;

readonly class ScenarioService
{
    public function __construct(
        private ScenarioRepository $scenarioRepository,
    ) {}

    /**
     * @throws Exception
     */
    public function findByUUID(string $uuid): ?Scenario
    {
        return $this->scenarioRepository->findOneBy(
            [
                'UUID'      => $uuid,
                'deletedAt' => null,
            ]
        );
    }

    public function findByAlias(TargetAliasEnum $alias): ?Scenario
    {
        return $this->scenarioRepository->findOneBy(
            [
                'alias'     => $alias->value,
                'deletedAt' => null,
            ]
        );
    }

    public function findScenarioByTarget(TargetAliasEnum $targetAliasEnum): ?Scenario
    {
        return match ($targetAliasEnum) {
            TargetAliasEnum::Main         => $this->findByAlias(TargetAliasEnum::Main),
            TargetAliasEnum::Cart         => $this->findByAlias(TargetAliasEnum::Cart),
            TargetAliasEnum::PlaceAnOrder => $this->findByAlias(TargetAliasEnum::PlaceAnOrder),
            default                       => null,
        };
    }

    public function findScenarioByNameAndType(string $type, string $name): ?Scenario
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
    public function generateDefaultScenario(Bot $bot): Scenario
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
            ->setProjectId($bot->getProjectId())
            ->setBotId($bot->getId())
            ->setContract($scenarioContractDto);

        $this->scenarioRepository->saveAndFlush($scenarioEntity);

        return $scenarioEntity;
    }

    public function removeAllScenarioForBot(Bot $bot): void
    {
        $scenarios = $this->scenarioRepository->findBy(
            [
                'projectId' => $bot->getProjectId(),
                'botId'     => $bot->getId(),
            ]
        );

        foreach ($scenarios as $scenario) {
            $scenario->markAtDeleted();

            $this->scenarioRepository->saveAndFlush($scenario);
        }
    }
}

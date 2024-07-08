<?php

namespace App\Service\Visitor\Scenario;

use App\Dto\Scenario\ScenarioContractDto;
use App\Dto\Scenario\ScenarioKeyboardDto;
use App\Entity\Scenario\Scenario;
use App\Enum\NavigateEnum;
use App\Repository\Scenario\ScenarioRepository;
use App\Service\Constructor\Core\Helper\JumpHelper;
use App\Service\Constructor\Core\Jumps\JumpResolver;
use Exception;

class ScenarioService
{
    public const SCENARIO_DEFAULT = 'default';

    public const SCENARIO_MAIN = 'main';

    public const SCENARIO_CART = 'cart';

    public function __construct(
        private readonly ScenarioRepository $scenarioRepository,
        private readonly JumpResolver $jumpResolver,
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
            $scenario = $this->getDefaultScenario();
        }

        if (null === $scenario) {
            throw new Exception('Нет сценария по умолчанию');
        }

        return $scenario;
    }

    public function getDefaultScenario(): ?Scenario
    {
        return $this->scenarioRepository->findOneBy(
            [
                'alias'     => static::SCENARIO_DEFAULT,
                'deletedAt' => null,
            ]
        );
    }

    public function getMainScenario(): ?Scenario
    {
        return $this->scenarioRepository->findOneBy(
            [
                'alias'     => static::SCENARIO_MAIN,
                'deletedAt' => null,
            ]
        );
    }

    public function getCartScenario(): ?Scenario
    {
        return $this->scenarioRepository->findOneBy(
            [
                'alias'     => static::SCENARIO_CART,
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
            $scenario = $this->jumpResolver->resolveScenario($targetEnum);
        }

        if (is_null($scenario)) {
            $scenario = $this->getDefaultScenario();
        }

        if (is_null($scenario)) {
            throw new Exception('Нет сценария по умолчанию');
        }

        return $scenario;
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

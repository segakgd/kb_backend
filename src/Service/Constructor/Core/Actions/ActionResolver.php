<?php

namespace App\Service\Constructor\Core\Actions;

use App\Dto\SessionCache\Cache\CacheActionDto;
use App\Service\Constructor\ActionProvider;
use App\Service\Constructor\Core\Dto\Responsible;
use Exception;

readonly class ActionResolver
{
    public function __construct(
        private ActionProvider $actionProvider,
    ) {}

    /**
     * @param array<CacheActionDto> $actions
     *
     * @throws Exception
     */
    public function resolve(Responsible $responsible, array $actions): array
    {
        $count = count($actions);
        $now = 1;
        $nextAction = null;

        foreach ($actions as $key => $action) {
            if ($action->isFinished()) {
                continue;
            }

            if ($now < $count) {
                $nextKey = $key + 1;
                $nextAction = $actions[$nextKey] ?? null;
            }

            $responsible->setAction($action);

            $actionInstance = $this->getActionInstance($responsible);

            $actionInstance->execute(
                responsible: $responsible,
                nextAction: $this->getNextAction($nextAction->getTarget())
            );

            break;
        }

        return $actions;
    }

    /**
     * @throws Exception
     */
    private function getActionInstance(Responsible $responsible): AbstractAction
    {
        $target = $responsible->getAction()->getTarget();

        return $this->actionProvider->getByTarget($target);
    }

    /**
     * @throws Exception
     */
    private function getNextAction(?string $targetNext): ?AbstractAction
    {
        if (is_null($targetNext)) {
            return null;
        }

        return $this->actionProvider->getByTarget($targetNext);
    }
}

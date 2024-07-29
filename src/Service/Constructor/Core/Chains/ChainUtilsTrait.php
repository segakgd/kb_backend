<?php

namespace App\Service\Constructor\Core\Chains;

use App\Service\Constructor\Core\Dto\Condition;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

trait ChainUtilsTrait
{
    protected function isValidContent(ResponsibleInterface $responsible, array $data): bool
    {
        $content = $responsible->getContent();

        if (in_array($content, $data)) {
            return true;
        }

        return false;
    }

    protected function makeCondition(array $replyMarkups = []): ConditionInterface
    {
        $condition = new Condition();

        if (!empty($replyMarkups)) {
            $condition->setKeyBoard($replyMarkups);
        }

        return $condition;
    }
}

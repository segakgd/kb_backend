<?php

namespace App\Service\System\Core\Chains\Items\Ecommerce\common;

use App\Service\System\Core\Chains\Items\AbstractChain;
use App\Service\System\Core\Dto\Condition;
use App\Service\System\Core\Dto\ConditionInterface;
use App\Service\System\Core\Dto\ResponsibleInterface;
use Exception;

class End extends AbstractChain
{
    /**
     * @throws Exception
     */
    public function success(ResponsibleInterface $responsible): ResponsibleInterface
    {
        return $responsible;
    }

    public function condition(): ConditionInterface
    {
        $replyMarkups = [
            [
                [
                    'text' => 'Поставить состояние для ' . static::class
                ],
            ],
        ];

        $condition = new Condition();

        $condition->setKeyBoard($replyMarkups);

        return $condition;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        return true;
    }
}

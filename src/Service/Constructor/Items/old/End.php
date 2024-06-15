<?php

namespace App\Service\Constructor\Items\old;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Dto\Responsible;
use Exception;

class End // extends AbstractChain // todo удалить
{
    /**
     * @throws Exception
     */
    public function success(Responsible $responsible, CacheDto $cacheDto): bool
    {
        return true;
    }

    public function fall(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $replyMarkups = [
            [
                [
                    'text' => 'вернуться в главное меню'
                ],
            ]
        ];

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            'Не понимаю о чем вы... мб вам...',
            null,
            $replyMarkups,
        );

        $responsible->getResult()->addMessage($responsibleMessage);

        return false;
    }

    public function validateCondition(string $content): bool
    {
        return false;
    }
}

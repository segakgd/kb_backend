<?php

namespace App\Service\System\Core\Chains\Items\Ecommerce\Cart;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\System\Core\Chains\Items\AbstractChain;
use App\Service\System\Core\Dto\Condition;
use App\Service\System\Core\Dto\ConditionInterface;
use App\Service\System\Core\Dto\Responsible;
use App\Service\System\Core\Dto\ResponsibleInterface;

class ContactChain extends AbstractChain
{
    public function success(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $cacheDto = $responsible->getCacheDto();
        $content = $cacheDto->getContent();
        $contacts = $cacheDto->getCart()->getContacts();

        $contacts['full'] = $content;

        $cacheDto->getCart()->setContacts($contacts);

        $replyMarkups = [
            [
                [
                    'text' => 'вернуться в главное меню'
                ],
            ]
        ];

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            "Отлично, $content а теперь пришли мне свой номер телефона",
            null,
            $replyMarkups,
        );

        $responsible->getResult()->addMessage($responsibleMessage);

        return true;
    }

    public function fall(Responsible $responsible, CacheDto $cacheDto): bool
    {
        return false;
    }

    public function validateCondition(string $content): bool
    {
        // todo проверить на стрингу? оО и мат

        return true;
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
        // TODO: Implement validate() method.
    }
}

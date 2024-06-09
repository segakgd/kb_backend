<?php

namespace App\Service\System\Core\Chains\Items\Ecommerce\Cart;

use App\Helper\MessageHelper;
use App\Service\System\Core\Chains\Items\AbstractChain;
use App\Service\System\Core\Dto\Condition;
use App\Service\System\Core\Dto\ConditionInterface;
use App\Service\System\Core\Dto\ResponsibleInterface;

class PhoneContactChain extends AbstractChain
{
    public function success(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $cacheDto = $responsible->getCacheDto();
        $content = $cacheDto->getContent();
        $contacts = $cacheDto->getCart()->getContacts();

        $contacts['phone'] = $content;

        $cacheDto->getCart()->setContacts($contacts);

        $replyMarkups = [
            [
                [
                    'text' => 'Указать адрес доставки'
                ],
                [
                    'text' => 'Самовывоз'
                ],
                [
                    'text' => 'вернуться в главное меню'
                ],
            ]
        ];

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            "Отлично, ваш номер телефон $content. Нужна ли вам доставка?",
            null,
            $replyMarkups,
        );

        $responsible->getResult()->addMessage($responsibleMessage);

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

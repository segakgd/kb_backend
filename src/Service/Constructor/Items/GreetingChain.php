<?php

namespace App\Service\Constructor\Items;

use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Chains\AbstractChain;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

class GreetingChain extends AbstractChain
{
    public function complete(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $message = "Приветствуем вас в нашем тестовом боте! \n\n Здесь вы можете пройти тестирование функционала, связанного с категориями товаров, акциями и предложениями. \n\n При помощи нашего бота вы сможете проверить работу различных возможностей без каких-либо финансовых обязательств. Для вашего удобства доступна также функция тестовой оплаты, позволяющая понять, как проходит процесс оплаты товаров. \n\n Наслаждайтесь использованием нашего бота и убедитесь в его эффективности для вашего бизнеса!";

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
        );

        $responsible->getResult()->setMessage($responsibleMessage);

        return $responsible;
    }

    public function condition(ResponsibleInterface $responsible): ConditionInterface
    {
        return $this->makeCondition();
    }

    public function perform(ResponsibleInterface $responsible): bool
    {
        return true;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        return true;
    }
}

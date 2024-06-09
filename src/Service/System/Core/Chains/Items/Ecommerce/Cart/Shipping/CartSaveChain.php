<?php

namespace App\Service\System\Core\Chains\Items\Ecommerce\Cart\Shipping;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\DealManager;
use App\Service\Common\Project\ProjectService;
use App\Service\System\Core\Chains\Items\AbstractChain;
use App\Service\System\Core\Dto\Condition;
use App\Service\System\Core\Dto\ConditionInterface;
use App\Service\System\Core\Dto\Responsible;
use App\Service\System\Core\Dto\ResponsibleInterface;

class CartSaveChain extends AbstractChain
{
    public function __construct(
        private readonly DealManager    $dealManager,
        private readonly ProjectService $projectService,
    ) {
    }

    public function success(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $cacheDto = $responsible->getCacheDto();
        $content = $cacheDto->getContent();
        $project = $this->projectService->findOneById(1);

        $this->dealManager->createDeal($project, $cacheDto->getCart());

        $message = "Отлично. Мы сохранили ваш заказ. Сумма вашего заказа составляет N рублей Хотете его оплатить сейчас?";

        $replyMarkups = [
            [
                [
                    'text' => 'Оплатить'
                ],
                [
                    'text' => 'Удалить заказ'
                ],
            ],
            [
                [
                    'text' => 'вернуться в главное меню'
                ],
            ],
        ];

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            $message,
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

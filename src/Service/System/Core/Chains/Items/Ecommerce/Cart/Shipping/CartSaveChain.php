<?php

namespace App\Service\System\Core\Chains\Items\Items\Cart\Shipping;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\DealManager;
use App\Service\Common\Project\ProjectService;
use App\Service\System\Core\Dto\Responsible;

class CartSaveChain // extends AbstractChain
{
    public function __construct(
        private readonly DealManager $dealManager,
        private readonly ProjectService $projectService,
    ) {
    }

    public function success(Responsible $responsible, CacheDto $cacheDto): bool
    {
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

        return true;
    }

    public function fall(Responsible $responsible, CacheDto $cacheDto): bool
    {
        return false;
    }

    public function validateCondition(string $content): bool
    {
        return true;
    }
}

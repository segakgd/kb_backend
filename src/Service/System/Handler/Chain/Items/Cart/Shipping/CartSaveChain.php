<?php

namespace App\Service\System\Handler\Chain\Items\Cart\Shipping;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\DealManager;
use App\Service\Common\Project\ProjectService;
use App\Service\System\Contract;
use App\Service\System\Handler\Chain\AbstractChain;

class CartSaveChain extends AbstractChain
{
    public function __construct(
        private readonly DealManager $dealManager,
        private readonly ProjectService $projectService,
    ) {
    }

    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();
        $project = $this->projectService->findOneById(4842);

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

        $contractMessage = MessageHelper::createContractMessage(
            $message,
            null,
            $replyMarkups,
        );

        $contract->addMessage($contractMessage);

        return true;
    }

    public function fall(Contract $contract, CacheDto $cacheDto): bool
    {
        return false;
    }

    public function validateCondition(string $content): bool
    {
        return true;
    }
}

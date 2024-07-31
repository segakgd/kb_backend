<?php

namespace App\Service\Constructor;

use App\Service\Constructor\Actions\Cart\CartFinishAction;
use App\Service\Constructor\Actions\Cart\CartStartAction;
use App\Service\Constructor\Actions\Ecommerce\CategoriesAction;
use App\Service\Constructor\Actions\Ecommerce\ProductsByCategoryAction;
use App\Service\Constructor\Actions\Ecommerce\StartAction;
use App\Service\Constructor\Actions\Ecommerce\VariantProductAction;
use App\Service\Constructor\Actions\Ecommerce\VariantsProductAction;
use App\Service\Constructor\Actions\FinishAction;
use App\Service\Constructor\Actions\Order\OrderContactsFullNameAction;
use App\Service\Constructor\Actions\Order\OrderContactsPhoneAction;
use App\Service\Constructor\Actions\Order\OrderFinishAction;
use App\Service\Constructor\Actions\Order\OrderGreetingAction;
use App\Service\Constructor\Actions\Order\OrderShippingAction;
use App\Service\Constructor\Actions\Order\OrderShippingSwitchAction;
use App\Service\Constructor\Core\Chains\AbstractAction;
use Exception;

readonly class ActionProvider
{
    public function __construct(
        private ProductsByCategoryAction $productsByCategoryChain,
        private CategoriesAction $productCategoryChain,
        private VariantsProductAction $variantsProductChain,
        private VariantProductAction $variantProductChain,
    ) {}

    /**
     * @throws Exception
     */
    public function getByTarget(string $actionName): AbstractAction
    {
        return match ($actionName) {
            StartAction::getName()              => new StartAction(),
            CategoriesAction::getName()         => $this->productCategoryChain,
            ProductsByCategoryAction::getName() => $this->productsByCategoryChain,
            VariantsProductAction::getName()    => $this->variantsProductChain,
            VariantProductAction::getName()     => $this->variantProductChain,
            FinishAction::getName()             => new FinishAction(),

            OrderGreetingAction::getName()         => new OrderGreetingAction(),
            OrderContactsFullNameAction::getName() => new OrderContactsFullNameAction(),
            OrderContactsPhoneAction::getName()    => new OrderContactsPhoneAction(),
            OrderShippingSwitchAction::getName()   => new OrderShippingSwitchAction(),
            OrderShippingAction::getName()         => new OrderShippingAction(),
            OrderFinishAction::getName()           => new OrderFinishAction(),

            CartFinishAction::getName() => new CartFinishAction(),
            CartStartAction::getName()  => new CartStartAction(),

            default => throw new Exception('Undefined action name'),
        };
    }
}

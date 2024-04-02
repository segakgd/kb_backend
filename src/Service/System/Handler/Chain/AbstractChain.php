<?php

namespace App\Service\System\Handler\Chain;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\JumpEnum;
use App\Service\System\Resolver\Dto\Contract;

abstract class AbstractChain
{
    public function chain(Contract $contract, CacheDto $cacheDto): bool
    {
        if ($cacheDto->getEvent()->getCurrentChain()->isRepeat()) {
            return $this->success($contract, $cacheDto);
        }

        if ($this->handleNavigate($cacheDto->getContent(), $contract)) {
            return true;
        }

        if ($this->validateCondition($cacheDto->getContent())) {
            return $this->success($contract, $cacheDto);
        }

        return $this->fall($contract, $cacheDto);
    }

    public function handleNavigate(string $content, Contract $contract): bool
    {
        $result = match ($content) {
            'вернуться в главное меню' => JumpEnum::Main,
            'Моя корзина' => JumpEnum::Cart,
            'вернуться к товарам' => JumpEnum::ShopProducts,
            'вернуться к категориям' => JumpEnum::ShowShopProductsCategory,
            default => null
        };

        if ($result) {
            $contract->setJump($result);

            return true;
        }

        return false;
    }

    abstract public function success(Contract $contract, CacheDto $cacheDto): bool;

    abstract public function fall(Contract $contract, CacheDto $cacheDto): bool;

    abstract public function validateCondition(string $content): bool;
}

<?php

namespace App\Service\Constructor\Core\Chains;

use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

interface ActionInterface
{
    public static function getName(): string;

    /**
     * Точка входа
     */
    public function execute(ResponsibleInterface $responsible, ?ActionInterface $nextChain): bool;

    /**
     * Единица, которая выполняется перед
     */
    public function before(ResponsibleInterface $responsible): bool;

    /**
     * Основное действие
     */
    public function complete(ResponsibleInterface $responsible): ResponsibleInterface;

    /**
     * Единица, которая выполняется перед
     */
    public function after(ResponsibleInterface $responsible): bool;

    /**
     * Условие которое нужно для прохождения данного чейна
     */
    public function condition(ResponsibleInterface $responsible): ConditionInterface;

    /**
     * Валидация того, что пришло с внешнего мира
     */
    public function validate(ResponsibleInterface $responsible): bool;

    /**
     * Чейн не прошёл (к примеру валидация)
     */
    public function fail(ResponsibleInterface $responsible): ResponsibleInterface;
}

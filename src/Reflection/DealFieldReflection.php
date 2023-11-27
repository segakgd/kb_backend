<?php

namespace App\Reflection;

use App\Entity\Lead\Deal;
use App\Service\Reflection\Model\AbstractReflection;
use DateTimeImmutable;

/**
 * @method int|null getId()
 *
 * @method void setId(int $id)
 *
 * @method string|null getName()
 *
 * @method $this setName(string $name)
 *
 * @method string|null getValue()
 *
 * @method $this setValue(string $value)
 *
 * @method Deal|null getDeal()
 *
 * @method $this setDeal(?Deal $deal)
 *
 * @method DateTimeImmutable|null getCreatedAt()
 *
 * @method $this setCreatedAt(DateTimeImmutable $createdAt)
 */
final class DealFieldReflection extends AbstractReflection
{}

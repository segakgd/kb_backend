<?php

namespace App\Reflection;

use App\Entity\Lead\DealContacts;
use App\Entity\Lead\DealField;
use App\Entity\Lead\DealOrder;
use App\Service\Reflection\Model\AbstractReflection;
use Doctrine\Common\Collections\Collection;

// todo коллекции нужно превратить во что-то другое

/**
 * @method int|null getId()
 *
 * @method DealContacts|null getContacts()
 *
 * @method  $this setContacts(DealContacts|null $contacts)
 *
 * @method DealOrder|null getOrder()
 *
 * @method  $this setOrder(DealOrder|null $order)
 *
 * @method  Collection getFields()
 *
 * @method  $this addField(DealField $field)
 *
 * @method  $this removeField(DealField $field)
 *
 * @method int|null getProjectId()
 *
 * @method  $this setProjectId(int $projectId)
 */
final class DealReflection extends AbstractReflection
{}

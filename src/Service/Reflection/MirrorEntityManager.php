<?php

namespace App\Service\Reflection;

use App\Entity\Lead\Deal;
use App\Entity\Lead\DealContacts;
use App\Entity\Lead\DealField;
use App\Exception\Reflection\UndefinedReflectionException;
use App\Reflection\DealContactsReflection;
use App\Reflection\DealFieldReflection;
use App\Reflection\DealReflection;
use App\Service\Reflection\Model\ReflectionInterface;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use Throwable;

/**
 * Менеджер для создания отражений(reflection) сущностей
 *
 * т.е по типу обычного зеркала, которое даёт отражение какого либо объекта внешнего мира
 */
class MirrorEntityManager
{
    private const REFLECTION_CLASS = [
        DealField::class => DealFieldReflection::class,
        Deal::class => DealReflection::class,
        DealContacts::class => DealContactsReflection::class,
    ];

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws UndefinedReflectionException
     */
    public function find(string $name, $id): ReflectionInterface
    {
        $entity = $this->entityManager->find($name, $id);

        return $this->reflect($entity);
    }

    /**
     * @throws UndefinedReflectionException
     */
    public function reflect(object $object)
    {
        $reflectionType = self::REFLECTION_CLASS[$object::class] ?? throw new UndefinedReflectionException($object::class);

        if (!$reflectionType){
            return null;
        }

        $phpReflectionObject = new ReflectionClass($object);
        $reflection = new $reflectionType();

        return $this->reflectProperties($phpReflectionObject, $reflection, $object);
    }

    private function reflectProperties($phpReflectionObject, $reflection, $object)
    {
        $props = $phpReflectionObject->getProperties();

        foreach ($props as $prop) {
            $value = $prop->getValue($object);

            $reflection->{$prop->getName()} = $value;
        }

        return $reflection;
    }

    public function save(string $name, int $id, ReflectionInterface $reflection): void
    {
        $entity = $this->entityManager->find($name, $id);

        foreach ($reflection as $reflectionKey => $reflectionValue){
            $reflectionMethod = 'set' . ucfirst($reflectionKey);

            try {
                $entity->$reflectionMethod($reflectionValue);
            } catch (Throwable){
                // todo костыль
            }
        }

        $this->entityManager->flush($entity);
        $this->entityManager->persist($entity);
    }
}

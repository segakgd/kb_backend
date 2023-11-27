<?php

namespace App\Service\Admin\Ecommerce\Shipping;

use App\Dto\Ecommerce\ShippingDto;
use App\Entity\Ecommerce\Shipping;
use App\Repository\Ecommerce\ShippingEntityRepository;
use Psr\Log\LoggerInterface;
use Throwable;

class ShippingManager implements ShippingManagerInterface
{
    public function __construct(
        private ShippingEntityRepository $shippingEntityRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function getOne(int $projectId, int $shippingId): ?Shipping
    {
        return $this->shippingEntityRepository->findOneBy(
            [
                'id' => $shippingId,
                'projectId' => $projectId
            ]
        );
    }

    public function getAll(int $projectId): array
    {
        return $this->shippingEntityRepository->findBy(
            [
                'projectId' => $projectId
            ]
        );
    }

    public function add(ShippingDto $shippingDto, int $projectId): Shipping
    {
        $entity = (new Shipping());

        if ($name = $shippingDto->getTitle()){
            $entity->setTitle($name);
        }

        if ($type = $shippingDto->getType()){
            $entity->setType($type);
        }

        if ($price = $shippingDto->getPrice()){
            $entity->setPrice($price->toArray());
        }

        $entity->setProjectId($projectId);

        $this->shippingEntityRepository->saveAndFlush($entity);

        return $entity;
    }

    public function update(ShippingDto $shippingDto, int $projectId, int $shippingId): Shipping
    {
        $entity = $this->getOne($projectId, $shippingId);

        if ($name = $shippingDto->getTitle()){
            $entity->setTitle($name);
        }

        if ($type = $shippingDto->getType()){
            $entity->setType($type);
        }

        if ($price = $shippingDto->getPrice()){
            $entity->setPrice($price->toArray());
        }

        $this->shippingEntityRepository->saveAndFlush($entity);

        return $entity;
    }

    public function remove(int $projectId, int $shippingId): bool
    {
        $shippingEntity = $this->getOne($projectId, $shippingId);

        try {
            if ($shippingEntity){
                $this->shippingEntityRepository->removeAndFlush($shippingEntity);
            }

        } catch (Throwable $exception){
            $this->logger->error($exception->getMessage());

            return false;
        }

        return true;
    }
}
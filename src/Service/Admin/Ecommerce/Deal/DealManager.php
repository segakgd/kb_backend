<?php

namespace App\Service\Admin\Ecommerce\Deal;

use App\Dto\Ecommerce\DealDto;
use App\Dto\Ecommerce\OrderDto;
use App\Entity\Lead\Deal;
use App\Entity\Lead\DealOrder;
use App\Repository\Lead\DealEntityRepository;
use Psr\Log\LoggerInterface;
use Throwable;

class DealManager implements DealManagerInterface
{
    public function __construct(
        private readonly DealEntityRepository $dealEntityRepository,
        private readonly ContactManager $contactService,
        private readonly FieldManager $fieldService,
        private readonly OrderManager $orderService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function getAll(int $projectId): array
    {
        return $this->dealEntityRepository->findBy(
            [
                'projectId' => $projectId
            ]
        );
    }

    public function getOne(int $projectId, int $dealId): ?Deal
    {
        return $this->dealEntityRepository->findOneBy(
            [
                'id' => $dealId,
                'projectId' => $projectId
            ]
        );
    }

    public function add(DealDto $dealDto, int $projectId): Deal
    {
        $entity = (new Deal());

        if ($contacts = $dealDto->getContacts()){
            // todo мы можем добавить сущесвтующие контакты.
            $contacts = $this->contactService->add($contacts);

            $entity->setContacts($contacts);
        }

        if ($fieldsDto = $dealDto->getFields()){
            if ($entity->getFields()->count()){
                $fieldsEntity = $entity->getFields();

                foreach ($fieldsDto as $fieldDto){
                    $isUpdated = false;

                    foreach ($fieldsEntity as $fieldEntity){
                        if ($fieldDto->getId() === $fieldEntity->getId()){
                            $fieldEntity = $this->fieldService->update($fieldDto);

                            $entity->addField($fieldEntity);

                            $isUpdated = true;
                        }
                    }

                    if (!$isUpdated){
                        $fieldEntity = $this->fieldService->add($fieldDto);

                        $entity->addField($fieldEntity);
                    }
                }

            } else {
                foreach ($fieldsDto as $fieldDto){
                    $fieldEntity = $this->fieldService->add($fieldDto);

                    $entity->addField($fieldEntity);
                }
            }
        }

        if ($order = $dealDto->getOrder()){
            $orderEntity = $this->orderService->add($order);

            $entity->setOrder($orderEntity);
        }

        $entity->setProjectId($projectId);

        $this->dealEntityRepository->saveAndFlush($entity);

        return $entity;
    }

    public function update(DealDto $dealDto, int $projectId, int $dealId): Deal
    {
        $entity = $this->getOne($projectId, $dealId);

        if ($contacts = $dealDto->getContacts()){
            $contacts = $this->contactService->update($contacts);

            $entity->setContacts($contacts);
        }

        if ($fieldsDto = $dealDto->getFields()){
            if ($entity->getFields()->count()){
                $fieldsEntity = $entity->getFields();

                foreach ($fieldsDto as $fieldDto){
                    $isUpdated = false;

                    foreach ($fieldsEntity as $fieldEntity){
                        if ($fieldDto->getId() === $fieldEntity->getId()){
                            $fieldEntity = $this->fieldService->update($fieldDto);

                            $entity->addField($fieldEntity);

                            $isUpdated = true;
                        }
                    }

                    if (!$isUpdated){
                        $fieldEntity = $this->fieldService->add($fieldDto);

                        $entity->addField($fieldEntity);
                    }
                }

            } else {
                foreach ($fieldsDto as $fieldDto){
                    $fieldEntity = $this->fieldService->add($fieldDto);

                    $entity->addField($fieldEntity);
                }
            }
        }

        if ($order = $dealDto->getOrder()){
            if ($entity->getOrder()){
                $entity->setOrder(self::mapToExistEntity($order, $entity->getOrder()));
            } else {
                $entity->setOrder(self::mapToExistEntity($order, (new DealOrder)));
            }
        }

        $this->dealEntityRepository->saveAndFlush($entity);

        return $entity;
    }

    public function remove(int $projectId, int $dealId): bool
    {
        $deal = $this->getOne($projectId, $dealId);

        try {
            if ($deal){
                $this->dealEntityRepository->removeAndFlush($deal);
            }

        } catch (Throwable $exception){
            $this->logger->error($exception->getMessage());

            return false;
        }

        return true;
    }

    public static function mapToExistEntity(OrderDto $dto, DealOrder $entity): DealOrder
    {
        if ($products = $dto->getProducts()){
            foreach ($products as $product){
                $entity->addProduct($product);
            }
        }

        if ($promotions = $dto->getPromotions()){
            foreach ($promotions as $promotion){
                $entity->addPromotion($promotion);
            }
        }

        if ($shipping = $dto->getShipping()){
            $entity->setShipping($shipping->toArray());
        }

        if ($totalAmount = $dto->getTotalAmount()){
            $entity->setTotalAmount($totalAmount);
        }

        return $entity;
    }
}

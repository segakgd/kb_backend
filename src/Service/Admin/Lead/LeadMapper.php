<?php

declare(strict_types=1);

namespace App\Service\Admin\Lead;

use App\Controller\Admin\Lead\DTO\Request\Order\Product\OrderVariantReqDto;
use App\Controller\Admin\Lead\DTO\Response\Fields\LeadContactsRespDto;
use App\Controller\Admin\Lead\DTO\Response\Fields\LeadFieldRespDto;
use App\Controller\Admin\Lead\DTO\Response\LeadRespDto;
use App\Controller\Admin\Lead\DTO\Response\Order\OrderRespDto;
use App\Controller\Admin\Lead\DTO\Response\Order\Product\ProductRespDto;
use App\Controller\Admin\Lead\DTO\Response\Order\Product\ProductVariantRespDto;
use App\Entity\Lead\Deal;
use App\Repository\Ecommerce\ProductVariantRepository;

class LeadMapper
{
    public function __construct(
        private readonly ProductVariantRepository $productVariantRepository,
    ) {
    }

    public function mapArrayToResponse(array $deals): array
    {
        return array_map(function (Deal $deal) {
            return $this->mapToResponse($deal);
        }, $deals);
    }

    public function mapToResponse(Deal $deal): LeadRespDto
    {
        $leadContactsRespDto = $this->mapContactsToResponse($deal);
        $fieldsRespArray = $this->mapFieldsToResponse($deal);
        $orderDto = $this->mapOrderToResponse($deal);

        return (new LeadRespDto())
            ->setContacts($leadContactsRespDto)
            ->setFields($fieldsRespArray)
            ->setNumber($deal->getId())
            ->setOrder($orderDto);
    }

    private function mapContactsToResponse(Deal $deal): LeadContactsRespDto
    {
        $leadContactsRespDto = new LeadContactsRespDto();
        $contacts = $deal->getContacts();

        if (null === $contacts) {
            return $leadContactsRespDto;
        }

        if ($contacts->getEmail()) {
            $emailField = (new LeadFieldRespDto())
                ->setName('email')
                ->setValue($contacts->getEmail());

            $leadContactsRespDto->setMail($emailField);
        }

        if ($contacts->getPhone()) {
            $phoneField = (new LeadFieldRespDto())
                ->setName('phone')
                ->setValue($contacts->getPhone());

            $leadContactsRespDto->setPhone($phoneField);
        }

        if ($contacts->getLastName() || $contacts->getFirstName()) {
            $fullName = ($contacts->getFirstName() ?? '') . ' ' . ($contacts->getLastName() ?? '');

            $fullNameField = (new LeadFieldRespDto())
                ->setName('fullName')
                ->setValue($fullName);

            $leadContactsRespDto->setFullName($fullNameField);
        }

        return $leadContactsRespDto;
    }

    private function mapFieldsToResponse(Deal $deal): array
    {
        $fields = $deal->getFields();

        $fieldsArray = [];

        foreach ($fields as $field) {
            $fieldDto = (new LeadFieldRespDto())
                ->setValue($field->getValue())
                ->setName($field->getName());

            $fieldsArray[] = $fieldDto;
        }

        return $fieldsArray;
    }

    private function mapOrderToResponse(Deal $deal): OrderRespDto
    {
        $order = $deal->getOrder();
        $orderResponseDto = (new OrderRespDto());

        if ($order !== null) {
            $orderResponseDto->setCreatedAt($order->getCreatedAt());
        }

        $products = [];

        /** @var OrderVariantReqDto $variantDto */
        foreach ($order->getProductVariants() as $variantDto) {
            $productVariant = $this->productVariantRepository->find($variantDto->getId());

            $name = $productVariant?->getProduct()?->getName();

            $productRespDto = new ProductRespDto();

            $productVariantRespDto = (new ProductVariantRespDto())
                ->setPrice($variantDto->getPrice())
                ->setCount($variantDto->getCount())
                ->setName($productVariant->getName());

            $productRespDto
                ->setVariant($productVariantRespDto)
                ->setName($name);

            $products[] = $productRespDto;
        }

        $orderResponseDto->setProducts($products);

        return $orderResponseDto;
    }
}
